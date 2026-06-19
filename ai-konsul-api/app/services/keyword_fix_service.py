from __future__ import annotations
import re
from functools import lru_cache
from typing import Optional
from rapidfuzz import fuzz, process
from sentence_transformers import SentenceTransformer, util

# ─────────────────────────────────────────────────────────────────────────────
# Konfigurasi Threshold (Batas toleransi error ketik & semantik)
# ─────────────────────────────────────────────────────────────────────────────
FUZZY_TYPO_THRESHOLD: int   = 72    # Untuk Rapidfuzz (koreksi typo karakter)
SEMANTIC_THRESHOLD:   float = 0.85  # Untuk Cosine Similarity (koreksi semantik/sinonim)

# Mapping label UI
_CATEGORY_LABEL: dict[str, str] = {
    "product":    "[Product] Jenis produk",
    "problem":    "[Problem] Keluhan kulit",
    "ingredient": "[Ingredient] Kandungan aktif",
    "skin_type":  "[Skin Type] Jenis kulit",
}

def _category_label(category: str) -> str:
    return _CATEGORY_LABEL.get(category, category)

# ─────────────────────────────────────────────────────────────────────────────
# Inisialisasi Model NLP (Lazy Load - Hanya jalan kalau Layer 2 dipakai)
# ─────────────────────────────────────────────────────────────────────────────
_MODEL: Optional[SentenceTransformer] = None

def _get_model() -> SentenceTransformer:
    global _MODEL
    if _MODEL is None:
        _MODEL = SentenceTransformer("paraphrase-multilingual-MiniLM-L12-v2")
    return _MODEL

@lru_cache(maxsize=512)
def _keyword_embedding(kw_baku: str):
    return _get_model().encode([kw_baku], convert_to_tensor=True)

# ─────────────────────────────────────────────────────────────────────────────
# LAYER 2.1: RAPIDFUZZ (Mengatasi salah ketik huruf. Contoh: niacnamid -> niacinamide)
# ─────────────────────────────────────────────────────────────────────────────
def _fuzzy_correct(
    query_words: list[str],
    kw_baku: str,
) -> Optional[tuple[str, float]]:

    best = process.extractOne(
        kw_baku,
        query_words,
        scorer=fuzz.ratio,
        score_cutoff=FUZZY_TYPO_THRESHOLD,
    )

    if best is None:
        return None

    kata_asli, score, _ = best
    if kata_asli == kw_baku: return None # Jika sudah benar, skip

    return kata_asli, round(score / 100, 4)

# ─────────────────────────────────────────────────────────────────────────────
# LAYER 2.2: SEMANTIC FALLBACK (Mengatasi beda bahasa. Contoh: sunblock -> sunscreen)
# ─────────────────────────────────────────────────────────────────────────────
def _semantic_correct(
    query_words: list[str],
    query_embeddings,
    kw_baku: str,
) -> Optional[tuple[str, float]]:

    kw_emb = _keyword_embedding(kw_baku)
    scores = util.cos_sim(kw_emb, query_embeddings)[0]

    best_idx   = scores.argmax().item()
    best_score = scores[best_idx].item()

    if best_score < SEMANTIC_THRESHOLD: return None

    kata_asli = query_words[best_idx]
    if kata_asli == kw_baku: return None # Jika sudah benar, skip

    return kata_asli, round(best_score, 4)

# ─────────────────────────────────────────────────────────────────────────────
# ORKESTRASI KOREKSI & REKONSTRUKSI TEKS
# ─────────────────────────────────────────────────────────────────────────────
def _run_correction_pipeline(
    query_words:      list[str],
    kw_baku:          str,
    query_embeddings,
) -> Optional[dict]:
    
    # Coba koreksi typo huruf dulu (lebih ringan)
    result = _fuzzy_correct(query_words, kw_baku)
    if result:
        kata_asli, score = result
        return {"kata_asli": kata_asli, "kata_baku": kw_baku, "score": score, "metode": "string_distance"}

    # Jika gagal dan punya embedding, coba koreksi makna semantiknya
    if query_embeddings is None: return None

    result = _semantic_correct(query_words, query_embeddings, kw_baku)
    if result:
        kata_asli, score = result
        return {"kata_asli": kata_asli, "kata_baku": kw_baku, "score": score, "metode": "semantic"}

    return None

def _deduplicate_adjacent_tokens(text: str) -> str:
    return re.sub(r'\b(\w+)(?:\s+\1\b)+', r'\1', text, flags=re.IGNORECASE)

def _reconstruct_query(original_query: str, fix_result: dict) -> str:
    fixed = original_query.lower()
    for info in fix_result.values():
        fixed = re.sub(rf'\b{re.escape(info["kata_asli"])}\b', info["kata_baku"], fixed)
    return _deduplicate_adjacent_tokens(fixed)

# ─────────────────────────────────────────────────────────────────────────────
# [LOGGER LAYER 2] - Memonitor perubahan kata
# ─────────────────────────────────────────────────────────────────────────────
def _log_fix_result(fix_result: dict, fixed_query: Optional[str]) -> None:
    print("\n" + "="*60)
    print("🛠️ [LAYER 2] TYPO & SEMANTIC CORRECTION")
    print("-" * 60)

    if fix_result:
        print("  ✔️ Kata berhasil dikoreksi:")
        for key, info in fix_result.items():
            print(f"     '{info['kata_asli']}' → '{info['kata_baku']}' (Score: {info['score']}, Metode: {info['metode']})")

    if fixed_query:
        print(f"\n  Fixed query : \"{fixed_query}\"")

    status = "FIXED ✔" if fixed_query else "INCOMPLETE ✘"
    print(f"  Status      : {status}")
    print("="*60 + "\n")

# ─────────────────────────────────────────────────────────────────────────────
# PUBLIC API
# ─────────────────────────────────────────────────────────────────────────────
def fix_query(
    original_query:   str,
    cleaned_text:     str,
    fixable_keywords: dict[str, list[str]],
) -> dict:
    query_words = cleaned_text.split()
    fix_result: dict = {}
    query_embeddings = None

    for category, keywords in fixable_keywords.items():
        if not keywords: continue

        if query_embeddings is None:
            query_embeddings = _get_model().encode(query_words, convert_to_tensor=True)

        label = _category_label(category)

        for kw_baku in keywords:
            correction = _run_correction_pipeline(query_words, kw_baku, query_embeddings)
            if correction:
                fix_result[f"{label}::{kw_baku}"] = correction

    if not fix_result:
        _log_fix_result(fix_result, fixed_query=None)
        return {"is_fixable": False, "fix_result": {}, "fixed_query": None}

    fixed_query = _reconstruct_query(original_query, fix_result)
    _log_fix_result(fix_result, fixed_query)

    return {"is_fixable": True, "fix_result": fix_result, "fixed_query": fixed_query}