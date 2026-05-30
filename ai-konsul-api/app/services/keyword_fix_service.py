"""
keyword_fix_service.py
======================
Service koreksi keyword query — independen penuh dari validation layer.

Tanggung jawab service ini:
    ✔  Menerima query words + dict keyword per kategori dari pemanggil
    ✔  Layer 1: RapidFuzz — koreksi typo karakter (primary)
    ✔  Layer 2: MiniLM   — semantic fallback jika Layer 1 gagal
    ✔  Rekonstruksi query hasil koreksi
    ✔  Return correction result

Tidak boleh mengetahui:
    ✘  Status valid/invalid query
    ✘  Missing category
    ✘  Matched/unmatched validator result
    ✘  validate_query() atau modul Quality_querycontrol secara langsung

Kontrak input dari pemanggil (query_pipeline_service):
    fix_query(
        original_query  : str,
        cleaned_text    : str,
        fixable_keywords: dict[str, list[str]]
            # Contoh: {
            #     "problem":    ["jerawat", "komedo"],
            #     "product":    ["toner"],
            #     "constraint": ["niacinamide"],
            #     "skin_type":  ["kulit berminyak"],
            # }
            # Disiapkan oleh Quality_querycontrol, diteruskan oleh pipeline.
    )
"""

from __future__ import annotations

import re
from functools import lru_cache
from typing import Optional

from rapidfuzz import fuzz, process
from sentence_transformers import SentenceTransformer, util

# ─────────────────────────────────────────────────────────────────────────────
# Konfigurasi threshold
# ─────────────────────────────────────────────────────────────────────────────

FUZZY_TYPO_THRESHOLD: int   = 72    # rapidfuzz ratio  — koreksi typo karakter
SEMANTIC_THRESHOLD:   float = 0.80  # cosine similarity — semantic fallback

# ─────────────────────────────────────────────────────────────────────────────
# Mapping kategori → label display (opsional, hanya untuk output fix_result)
# ─────────────────────────────────────────────────────────────────────────────

_CATEGORY_LABEL: dict[str, str] = {
    "product":    "[Product] Jenis produk",
    "problem":    "[Problem] Keluhan kulit",
    "constraint": "[Constraint] Kandungan aktif",
    "skin_type":  "[Area/Type] Jenis kulit",
}


def _category_label(category: str) -> str:
    """Kembalikan label display untuk kategori. Fallback ke kategori itu sendiri."""
    return _CATEGORY_LABEL.get(category, category)


# ─────────────────────────────────────────────────────────────────────────────
# Model singleton — lazy load, dimuat sekali saat Layer 2 pertama kali dipakai
# ─────────────────────────────────────────────────────────────────────────────

_MODEL: Optional[SentenceTransformer] = None


def _get_model() -> SentenceTransformer:
    """Lazy singleton — model tidak dimuat jika Layer 1 cukup untuk semua koreksi."""
    global _MODEL
    if _MODEL is None:
        _MODEL = SentenceTransformer("paraphrase-multilingual-MiniLM-L12-v2")
    return _MODEL


# ─────────────────────────────────────────────────────────────────────────────
# Keyword embedding cache — per keyword baku, tidak di-encode ulang
# ─────────────────────────────────────────────────────────────────────────────

@lru_cache(maxsize=512)
def _keyword_embedding(kw_baku: str):
    """
    Cache embedding per keyword baku.
    Keyword yang sama antar request tidak di-encode ulang selama proses berjalan.
    """
    return _get_model().encode([kw_baku], convert_to_tensor=True)


# ─────────────────────────────────────────────────────────────────────────────
# Layer 1: RapidFuzz — Koreksi Typo Karakter
# ─────────────────────────────────────────────────────────────────────────────

def _fuzzy_correct(
    query_words: list[str],
    kw_baku: str,
) -> Optional[tuple[str, float]]:
    """
    Cari kata typo di query menggunakan string distance (RapidFuzz).

    Contoh kasus yang ditangani:
        jerwawat   → jerawat
        tonerr     → toner
        niacinamde → niacinamide

    Returns:
        (kata_asli, score) jika typo ditemukan, else None.
    """
    best = process.extractOne(
        kw_baku,
        query_words,
        scorer=fuzz.ratio,
        score_cutoff=FUZZY_TYPO_THRESHOLD,
    )

    if best is None:
        return None

    kata_asli, score, _ = best

    # Kata sudah benar — bukan koreksi
    if kata_asli == kw_baku:
        return None

    return kata_asli, round(score / 100, 4)


# ─────────────────────────────────────────────────────────────────────────────
# Layer 2: MiniLM Semantic — Fallback Synonym / Cross-language
# ─────────────────────────────────────────────────────────────────────────────

def _semantic_correct(
    query_words: list[str],
    query_embeddings,
    kw_baku: str,
) -> Optional[tuple[str, float]]:
    """
    Cari kata semantik serupa di query menggunakan cosine similarity (MiniLM).

    Contoh kasus yang ditangani:
        acne      → jerawat
        oily      → berminyak
        dull skin → kusam

    Args:
        query_embeddings: Tensor hasil encode query_words — harus di-encode
                          di luar fungsi ini (satu kali per fix_query call).

    Returns:
        (kata_asli, score) jika match semantik ditemukan, else None.
    """
    kw_emb = _keyword_embedding(kw_baku)
    scores = util.cos_sim(kw_emb, query_embeddings)[0]

    best_idx   = scores.argmax().item()
    best_score = scores[best_idx].item()

    if best_score < SEMANTIC_THRESHOLD:
        return None

    kata_asli = query_words[best_idx]

    # Kata sudah benar — bukan koreksi
    if kata_asli == kw_baku:
        return None

    return kata_asli, round(best_score, 4)


# ─────────────────────────────────────────────────────────────────────────────
# Correction Pipeline — orkestrasi Layer 1 → Layer 2
# ─────────────────────────────────────────────────────────────────────────────

def _run_correction_pipeline(
    query_words:      list[str],
    kw_baku:          str,
    query_embeddings,
) -> Optional[dict]:
    """
    Jalankan Layer 1. Jika gagal, lanjut ke Layer 2.
    Layer 2 hanya dipanggil jika query_embeddings sudah tersedia.

    Returns:
        Dict detail koreksi, atau None jika tidak ada koreksi ditemukan.
    """
    # Layer 1: RapidFuzz — typo karakter
    result = _fuzzy_correct(query_words, kw_baku)
    if result:
        kata_asli, score = result
        return {
            "kata_asli": kata_asli,
            "kata_baku": kw_baku,
            "score":     score,
            "metode":    "string_distance",
        }

    # Layer 2: MiniLM Semantic — hanya jika Layer 1 gagal
    if query_embeddings is None:
        return None

    result = _semantic_correct(query_words, query_embeddings, kw_baku)
    if result:
        kata_asli, score = result
        return {
            "kata_asli": kata_asli,
            "kata_baku": kw_baku,
            "score":     score,
            "metode":    "semantic",
        }

    return None


# ─────────────────────────────────────────────────────────────────────────────
# Deduplication — hapus token identik yang langsung berurutan
# ─────────────────────────────────────────────────────────────────────────────

def _deduplicate_adjacent_tokens(text: str) -> str:
    """
    Hapus token identik yang langsung berurutan saja.

        "acid acid jerawat"            → "acid jerawat"       ✔
        "kulit berminyak kulit kering" → tidak berubah         ✔ (tidak berurutan)

    Tidak menggunakan global unique — aman untuk frasa multi-token.
    """
    return re.sub(r'\b(\w+)(?:\s+\1\b)+', r'\1', text, flags=re.IGNORECASE)


# ─────────────────────────────────────────────────────────────────────────────
# Query Reconstruction — terapkan koreksi ke original_query
# ─────────────────────────────────────────────────────────────────────────────

def _reconstruct_query(original_query: str, fix_result: dict) -> str:
    """
    Terapkan semua koreksi ke original_query, lalu jalankan deduplication.
    Menggunakan word-boundary regex agar tidak mengganti substring parsial.
    """
    fixed = original_query.lower()

    for info in fix_result.values():
        fixed = re.sub(
            rf'\b{re.escape(info["kata_asli"])}\b',
            info["kata_baku"],
            fixed,
        )

    return _deduplicate_adjacent_tokens(fixed)


# ─────────────────────────────────────────────────────────────────────────────
# Logging
# ─────────────────────────────────────────────────────────────────────────────

def _log_fix_result(fix_result: dict, fixed_query: Optional[str]) -> None:
    print("\n── After Query Fixing ──────────────────────────────")

    if fix_result:
        print("✔ Kata dinormalisasi:")
        for key, info in fix_result.items():
            print(f"   [{key}]")
            print(
                f"   '{info['kata_asli']}' → '{info['kata_baku']}'  "
                f"(score: {info['score']}, metode: {info['metode']})"
            )

    if fixed_query:
        print(f"\n   Fixed query : \"{fixed_query}\"")

    status = "FIXED ✔" if fixed_query else "INCOMPLETE ✘"
    print(f"   Status      : {status}")
    print("────────────────────────────────────────────────────\n")


# ─────────────────────────────────────────────────────────────────────────────
# Public API
# ─────────────────────────────────────────────────────────────────────────────

def fix_query(
    original_query:   str,
    cleaned_text:     str,
    fixable_keywords: dict[str, list[str]],
) -> dict:
    """
    Perbaiki typo dan ketidaksesuaian kata kunci pada query pengguna.

    Service ini tidak mengetahui dan tidak memanggil validate_query().
    Semua keputusan tentang keyword mana yang perlu di-fixing sudah
    disiapkan oleh Quality_querycontrol dan diteruskan via `fixable_keywords`.

    Args:
        original_query:
            Query asli dari pengguna. Digunakan untuk rekonstruksi output.

        cleaned_text:
            Query yang sudah dibersihkan (lowercase, tanpa stopword).
            Digunakan sebagai sumber kata-kata yang akan dikoreksi.

        fixable_keywords:
            Dict keyword per kategori yang perlu dicocokkan.
            Disiapkan oleh Quality_querycontrol, diteruskan oleh pipeline.

            Format:
                {
                    "problem":    ["jerawat", "komedo"],
                    "product":    ["toner"],
                    "constraint": ["niacinamide"],
                    "skin_type":  ["kulit berminyak"],
                }

    Returns:
        {
            "is_fixable":  bool,
            "fix_result":  dict[str, dict],
            "fixed_query": str | None,
        }

    Contoh output fix_result:
        {
            "[Problem] Keluhan kulit::jerawat": {
                "kata_asli": "jerwawat",
                "kata_baku": "jerawat",
                "score":     0.9412,
                "metode":    "string_distance",
            }
        }
    """
    query_words = cleaned_text.split()
    fix_result: dict = {}

    # Query embeddings diinisialisasi lazy:
    # - Di-encode tepat SEKALI saat ada keyword yang lolos ke Layer 2
    # - Jika Layer 1 berhasil semua, MiniLM tidak pernah dipanggil sama sekali
    query_embeddings = None

    for category, keywords in fixable_keywords.items():
        if not keywords:
            continue

        # Encode query_words saat Layer 2 pertama kali dibutuhkan
        if query_embeddings is None:
            query_embeddings = _get_model().encode(
                query_words, convert_to_tensor=True
            )

        label = _category_label(category)

        for kw_baku in keywords:
            correction = _run_correction_pipeline(
                query_words, kw_baku, query_embeddings
            )
            if correction:
                fix_result[f"{label}::{kw_baku}"] = correction

    if not fix_result:
        _log_fix_result(fix_result, fixed_query=None)
        return {
            "is_fixable":  False,
            "fix_result":  {},
            "fixed_query": None,
        }

    fixed_query = _reconstruct_query(original_query, fix_result)
    _log_fix_result(fix_result, fixed_query)

    return {
        "is_fixable":  True,
        "fix_result":  fix_result,
        "fixed_query": fixed_query,
    }


# BACKUP FILE LAMA (jangan dikembalikan ke kode aktif, hanya untuk referensi jika diperlukan):

# import re
# from rapidfuzz import fuzz, process
# from sentence_transformers import SentenceTransformer, util
# from app.services.Quality_querycontrol import validate_query

# model = SentenceTransformer("paraphrase-multilingual-MiniLM-L12-v2")

# # Threshold string distance — cari kata typo di query
# FUZZY_TYPO_THRESHOLD  = 72   # rapidfuzz ratio: cocok untuk typo karakter
# # Threshold semantic — fallback jika string distance gagal
# SEMANTIC_THRESHOLD    = 0.80

# LABEL_TO_KEY = {
#     "[Product] Jenis produk":       "product",
#     "[Problem] Keluhan kulit":      "problem",
#     "[Constraint] Kandungan aktif": "constraint",
#     "[Area/Type] Jenis kulit":      "skin_type"
# }


# def _find_typo_word(query_words: list[str], kw_baku: str) -> tuple[str, float, str] | None:
#     # Lapis 1: String distance — prioritas utama untuk typo
#     best = process.extractOne(
#         kw_baku,
#         query_words,
#         scorer=fuzz.ratio,
#         score_cutoff=FUZZY_TYPO_THRESHOLD,
#     )
#     if best:
#         kata_asli, score, _ = best
#         if kata_asli != kw_baku:
#             return kata_asli, round(score / 100, 4), "string_distance"

#     # Lapis 2: Semantic similarity — fallback
#     kw_embedding   = model.encode([kw_baku],    convert_to_tensor=True)
#     word_embedding = model.encode(query_words,  convert_to_tensor=True)
#     scores         = util.cos_sim(kw_embedding, word_embedding)[0]

#     best_idx   = scores.argmax().item()
#     best_score = scores[best_idx].item()

#     if best_score >= SEMANTIC_THRESHOLD:
#         kata_asli = query_words[best_idx]
#         if kata_asli != kw_baku:
#             return kata_asli, round(best_score, 4), "semantic"

#     return None


# def _print_fix_result(fix_result: dict, fixed_query: str | None) -> None:
#     print("\n── After Query Fixing ──────────────────────────────")

#     if fix_result:
#         print("✔ Kata dinormalisasi:")
#         for label, info in fix_result.items():
#             print(f"   {label}")
#             print(f"   '{info['kata_asli']}' → '{info['kata_baku']}'  "
#                   f"(score: {info['score']}, metode: {info['metode']})")

#     if fixed_query:
#         print(f"\n   Fixed query : \"{fixed_query}\"")

#     status = "FIXED ✔" if fixed_query else "INCOMPLETE ✘"
#     print(f"   Status      : {status}")
#     print("────────────────────────────────────────────────────\n")


# def fix_query(cleaned_text: str, original_query: str) -> dict:
#     validated   = validate_query(cleaned_text)
#     query_words = cleaned_text.split()
#     fix_result  = {}

#     for key, match_data in validated["matched"].items():
#         fuzzy_hits = match_data.get("fuzzy", [])
#         if not fuzzy_hits:
#             continue

#         label = next((k for k, v in LABEL_TO_KEY.items() if v == key), None)
#         if not label:
#             continue

#         for kw_baku in fuzzy_hits:
#             result = _find_typo_word(query_words, kw_baku)
#             if result:
#                 kata_asli, score, metode = result
#                 fix_result[f"{label}::{kw_baku}"] = {
#                     "kata_asli": kata_asli,
#                     "kata_baku": kw_baku,
#                     "score":     score,
#                     "metode":    metode,
#                 }

#     if not fix_result:
#         _print_fix_result(fix_result, fixed_query=None)
#         return {
#             "is_fixable":  False,
#             "fix_result":  {},
#             "fixed_query": None,
#         }

#     fixed_query = original_query.lower()
#     for info in fix_result.values():
#         fixed_query = re.sub(
#             rf'\b{re.escape(info["kata_asli"])}\b',
#             info["kata_baku"],
#             fixed_query,
#         )

#     # ──────────────────────────────────────────────────────────────────
#     # PERBAIKAN: DEDUPLIKASI KATA GANDA (Contoh: "acid acid" -> "acid")
#     # ──────────────────────────────────────────────────────────────────
    
#     # 1. Menghapus kata identik yang berurutan (misal: "acid acid")
#     fixed_query = re.sub(r'\b(\w+)(?:\s+\1\b)+', r'\1', fixed_query, flags=re.IGNORECASE)
    
#     # 2. Menghapus kata baku yang mungkin bertabrakan (misal: "kulit" + "kulit berminyak")
#     # Kita pecah dan gabungkan kembali untuk membuang duplikat unik dalam urutan yang sama
#     words = fixed_query.split()
#     seen = set()
#     result_words = []
#     for w in words:
#         # Kita simpan kata yang sudah ada, tapi jika kata tersebut bagian dari keyword, 
#         # kita biarkan logic regex di atas yang menangani frasa.
#         # Untuk pembersihan sederhana, join spasi sudah cukup membantu.
#         if w not in seen:
#             result_words.append(w)
#             seen.add(w)

#     fixed_query = " ".join(result_words)
#     # ──────────────────────────────────────────────────────────────────

#     _print_fix_result(fix_result, fixed_query)
#     return {
#         "is_fixable":  True,
#         "fix_result":  fix_result,
#         "fixed_query": fixed_query,
#     }



# DOKUMENTASI LAMA (untuk referensi, jangan dikembalikan ke kode aktif):

# import re
# from rapidfuzz import fuzz, process
# from sentence_transformers import SentenceTransformer, util
# from app.services.Quality_querycontrol import validate_query

# model = SentenceTransformer("paraphrase-multilingual-MiniLM-L12-v2")

# # Threshold string distance 
# FUZZY_TYPO_THRESHOLD  = 72   # rapidfuzz ratio:  untuk typo karakter
# # Threshold semantic — fallback jika string distance gagal
# SEMANTIC_THRESHOLD    = 0.80

# LABEL_TO_KEY = {
#     "[Product] Jenis produk":       "product",
#     "[Problem] Keluhan kulit":      "problem",
#     "[Constraint] Kandungan aktif": "constraint",
#     "[Area/Type] Jenis kulit":      "skin_type"
# }


# def _find_typo_word(query_words: list[str], kw_baku: str) -> tuple[str, float, str] | None:
#     # Lapis 1: String distance — prioritas utama untuk typo
#     best = process.extractOne(
#         kw_baku,
#         query_words,
#         scorer=fuzz.ratio,
#         score_cutoff=FUZZY_TYPO_THRESHOLD,
#     )
#     if best:
#         kata_asli, score, _ = best
#         if kata_asli != kw_baku:
#             return kata_asli, round(score / 100, 4), "string_distance"

#     # Lapis 2: Semantic similarity — fallback
#     kw_embedding   = model.encode([kw_baku],    convert_to_tensor=True)
#     word_embedding = model.encode(query_words,  convert_to_tensor=True)
#     scores         = util.cos_sim(kw_embedding, word_embedding)[0]

#     best_idx   = scores.argmax().item()
#     best_score = scores[best_idx].item()

#     if best_score >= SEMANTIC_THRESHOLD:
#         kata_asli = query_words[best_idx]
#         if kata_asli != kw_baku:
#             return kata_asli, round(best_score, 4), "semantic"

#     return None


# def _print_fix_result(fix_result: dict, fixed_query: str | None) -> None:
#     print("\n── After Query Fixing ──────────────────────────────")

#     if fix_result:
#         print("✔ Kata dinormalisasi:")
#         for label, info in fix_result.items():
#             print(f"   {label}")
#             print(f"   '{info['kata_asli']}' → '{info['kata_baku']}'  "
#                   f"(score: {info['score']}, metode: {info['metode']})")

#     if fixed_query:
#         print(f"\n   Fixed query : \"{fixed_query}\"")

#     status = "FIXED ✔" if fixed_query else "INCOMPLETE ✘"
#     print(f"   Status      : {status}")
#     print("────────────────────────────────────────────────────\n")


# def fix_query(cleaned_text: str, original_query: str) -> dict:
#     validated   = validate_query(cleaned_text)
#     query_words = cleaned_text.split()
#     fix_result  = {}

#     for key, match_data in validated["matched"].items():
#         fuzzy_hits = match_data.get("fuzzy", [])
#         if not fuzzy_hits:
#             continue

#         label = next((k for k, v in LABEL_TO_KEY.items() if v == key), None)
#         if not label:
#             continue

#         for kw_baku in fuzzy_hits:
#             result = _find_typo_word(query_words, kw_baku)
#             if result:
#                 kata_asli, score, metode = result
#                 fix_result[f"{label}::{kw_baku}"] = {
#                     "kata_asli": kata_asli,
#                     "kata_baku": kw_baku,
#                     "score":     score,
#                     "metode":    metode,
#                 }

#     if not fix_result:
#         _print_fix_result(fix_result, fixed_query=None)
#         return {
#             "is_fixable":  False,
#             "fix_result":  {},
#             "fixed_query": None,
#         }

#     fixed_query = original_query.lower()
#     for info in fix_result.values():
#         fixed_query = re.sub(
#             rf'\b{re.escape(info["kata_asli"])}\b',
#             info["kata_baku"],
#             fixed_query,
#         )

#     _print_fix_result(fix_result, fixed_query)
#     return {
#         "is_fixable":  True,
#         "fix_result":  fix_result,
#         "fixed_query": fixed_query,
#     }