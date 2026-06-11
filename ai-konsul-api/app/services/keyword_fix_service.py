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
    "ingredient": "[Ingredient] Kandungan aktif",
    "skin_type":  "[Skin Type] Jenis kulit",
}


def _category_label(category: str) -> str:
    return _CATEGORY_LABEL.get(category, category)


# ─────────────────────────────────────────────────────────────────────────────
# Model singleton — lazy load, dimuat sekali saat Layer 2 pertama kali dipakai
# ─────────────────────────────────────────────────────────────────────────────

_MODEL: Optional[SentenceTransformer] = None


def _get_model() -> SentenceTransformer:
    global _MODEL
    if _MODEL is None:
        _MODEL = SentenceTransformer("paraphrase-multilingual-MiniLM-L12-v2")
    return _MODEL


# ─────────────────────────────────────────────────────────────────────────────
# Keyword embedding cache — per keyword baku, tidak di-encode ulang
# ─────────────────────────────────────────────────────────────────────────────

@lru_cache(maxsize=512)
def _keyword_embedding(kw_baku: str):
    return _get_model().encode([kw_baku], convert_to_tensor=True)


# ─────────────────────────────────────────────────────────────────────────────
# Layer 1: RapidFuzz — Koreksi Typo Karakter
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
    return re.sub(r'\b(\w+)(?:\s+\1\b)+', r'\1', text, flags=re.IGNORECASE)

# ─────────────────────────────────────────────────────────────────────────────
# Query Reconstruction — terapkan koreksi ke original_query
# ─────────────────────────────────────────────────────────────────────────────

def _reconstruct_query(original_query: str, fix_result: dict) -> str:
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

