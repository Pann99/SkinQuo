from __future__ import annotations
import re
from rapidfuzz import fuzz
from sentence_transformers import util

from app.services.keyword_manager import keyword_manager  
from app.services.keyword_fix_service import _get_model, _keyword_embedding 

LABEL_MAP: dict[str, str] = {
    "product":    "[Product] Jenis produk",
    "problem":    "[Problem] Keluhan kulit",
    "ingredient": "[Ingredient] Kandungan aktif",
    "skin_type":  "[Area/Type] Jenis kulit",
}

GENERIC_NOISE_KEYWORDS: frozenset[str] = frozenset({
    "kulit", "wajah", "muka", "produk", "skincare", "bahan", "rekomendasi", "pake", "saya", "buat", "ingin", "cari", "skin", "jenis", "tipe", "type"
})

DETECT_THRESHOLD_SINGLE: int = 78   
DETECT_THRESHOLD_PHRASE: int = 85   
FUZZY_SKIP_KEYWORDS: frozenset[str] = frozenset({"kulit", "skin", "jenis", "tipe", "type"})

SEMANTIC_THRESHOLD: float = 0.82  

def _get_ngrams(text: str, n: int) -> list[str]:
    words = text.split()
    if len(words) < n:
        return [text]
    return [" ".join(words[i : i + n]) for i in range(len(words) - n + 1)]

def _is_fuzzy_candidate(query_lower: str, keyword: str) -> bool:
    if len(keyword) <= 3:
        return False

    if keyword in FUZZY_SKIP_KEYWORDS or keyword in GENERIC_NOISE_KEYWORDS:
        return False
        
    kw_word_count = len(keyword.split())
    threshold     = DETECT_THRESHOLD_SINGLE if kw_word_count == 1 else DETECT_THRESHOLD_PHRASE
    candidates    = _get_ngrams(query_lower, kw_word_count)
    
    for candidate in candidates:
        # [FIX] Ganti WRatio menjadi token_sort_ratio agar "eye serum" tidak numpang lolos lewat kata "serum"
        scorer = fuzz.token_sort_ratio

        if scorer(keyword, candidate) >= threshold:
            return True
            
    return False

def _match_category_with_confidence(
    text_lower: str,
    original_text_lower: str,
    keywords: list[str],
    query_embedding,
    category_name: str
) -> tuple[list[dict], list[str]]:
    
    exact_found: list[dict] = []
    fixable_found: list[str] = []

    for kw in keywords:
        kw_lower = kw.lower().strip()
        if kw_lower in GENERIC_NOISE_KEYWORDS:
            continue

        if re.search(r'\b' + re.escape(kw_lower) + r'\b', text_lower):
            exact_found.append({"keyword": kw_lower, "confidence": 1.0, "method": "exact"})
            print(f"  [NLP-SCORE] ✔️ EXACT    | Kat: {category_name:<12} | Kata: '{kw_lower:<15}' | Score: 1.000")
            continue

        if _is_fuzzy_candidate(text_lower, kw_lower):
            fixable_found.append(kw_lower)
            exact_found.append({"keyword": kw_lower, "confidence": 0.85, "method": "fuzzy"})
            print(f"  [NLP-SCORE] 〰️ FUZZY    | Kat: {category_name:<12} | Kata: '{kw_lower:<15}' | Score: 0.850")
            continue

        kw_emb = _keyword_embedding(kw_lower)
        score = util.cos_sim(query_embedding, kw_emb).item()

        if score >= SEMANTIC_THRESHOLD:
            exact_found.append({"keyword": kw_lower, "confidence": round(score, 4), "method": "semantic"})
            print(f"  [NLP-SCORE] 🧠 SEMANTIC | Kat: {category_name:<12} | Kata: '{kw_lower:<15}' | Score: {score:.4f}")

    exact_found = sorted(exact_found, key=lambda x: x["confidence"], reverse=True)
    return exact_found, fixable_found

def validate_query(text: str, original_query: str = "") -> dict:
    text_lower = text.lower()
    semantic_target = original_query.lower() if original_query else text_lower

    matched:           dict[str, dict] = {}
    missing:           list[str]       = []
    fixable_keywords:  dict[str, list] = {}

    model = _get_model()
    query_embedding = model.encode(semantic_target, convert_to_tensor=True)

    print("\n" + "="*60)
    print(f"🔍 [LAYER 1] NLP SEMANTIC ENGINE ANALYSIS")
    print(f"Query Target : \"{semantic_target}\"")
    print("-" * 60)

    for category, keywords in keyword_manager.VALIDATION_KEYWORDS.items():
        exact_found, fixable_found = _match_category_with_confidence(
            text_lower, semantic_target, keywords, query_embedding, category
        )

        if exact_found or fixable_found:
            matched[category] = {
                "exact":              exact_found, 
                "fixable_candidates": fixable_found,
            }
            if fixable_found:
                fixable_keywords[category] = fixable_found
        else:
            missing.append(LABEL_MAP[category])

    print("="*60 + "\n")

    has_fixable   = bool(fixable_keywords)
    has_any_match = bool(matched)

    if not has_any_match:
        return {"status": "out_of_context", "missing": missing, "matched": matched}

    has_concern = "skin_type" in matched or "problem" in matched
    has_product = "product" in matched

    if not has_concern:
        nama_produk_diminta = "Produk"
        if has_product and matched.get("product", {}).get("exact"):
            nama_produk_diminta = matched["product"]["exact"][0]["keyword"].title()

        return {
            "status": "invalid",
            "missing": missing,
            "matched": matched,
            "message": f"Kamu mencari {nama_produk_diminta}, tapi untuk jenis kulit atau keluhan apa? (Contoh: '{nama_produk_diminta} untuk jerawat')"
        }

    if has_fixable:
        return {"status": "fixable", "missing": missing, "matched": matched, "fixable_keywords": fixable_keywords}

    return {"status": "valid", "missing": missing, "matched": matched}