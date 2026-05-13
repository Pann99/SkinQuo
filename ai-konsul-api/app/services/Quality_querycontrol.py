from rapidfuzz import fuzz

VALIDATION_KEYWORDS = {
    "product": [
        "toner", "serum", "moisturizer", "sunscreen", "cleanser", "essence",
        "exfoliant", "mask", "eye cream", "face wash", "pelembab", "sabun",
        "losion", "lotion", "ampoule", "mist", "primer", "bb cream", "cc cream"
    ],
    "problem": [
        "bruntusan", "jerawat", "acne", "kusam", "flek", "hiperpigmentasi",
        "pori-pori", "kering", "berminyak", "sensitif", "kemerahan", "redness",
        "kerutan", "penuaan", "aging", "bekas luka", "dark spot", "komedo"
    ],
    "constraint": [
        "niacinamide", "retinol", "vitamin c", "hyaluronic acid", "aha", "bha",
        "pha", "centella asiatica", "salicylic acid", "glycolic acid", "ceramide",
        "peptide", "zinc", "tea tree", "kojic acid", "arbutin", "snail mucin",
        "tranexamic acid", "azelaic acid", "bakuchiol"
    ],
    "skin_type": [
        "kulit kering", "kulit berminyak", "kulit kombinasi", "kulit sensitif",
        "kulit normal", "dry skin", "oily skin", "combination skin",
        "sensitive skin", "normal skin", "kulit", "semua jenis kulit", "all skin type"
    ]
}

LABEL_MAP = {
    "product":    "[Product] Jenis produk",
    "problem":    "[Problem] Keluhan kulit",
    "constraint": "[Constraint] Kandungan aktif",
    "skin_type":  "[Area/Type] Jenis kulit"
}

# Keyword terlalu pendek/umum — skip fuzzy agar tidak false positive
FUZZY_SKIP_KEYWORDS = {"kulit"}

# Threshold per panjang keyword (jumlah kata)
THRESHOLD_SINGLE = 75   # toleran untuk 1 kata — menangkap typo seperti jerwawat → jerawat
THRESHOLD_PHRASE = 92   # ketat untuk frasa — mencegah "kulit" cocok ke "kulit kering"


def _get_ngrams(text: str, n: int) -> list[str]:
    """Sliding window n-gram per kata untuk mencocokkan multi-word keyword."""
    words = text.split()
    if len(words) < n:
        return [text]
    return [" ".join(words[i:i + n]) for i in range(len(words) - n + 1)]


def _fuzzy_match_keyword(query_lower: str, keyword: str) -> bool:
  
    kw_words  = keyword.split()
    kw_length = len(kw_words)

    effective_threshold = THRESHOLD_SINGLE if kw_length == 1 else THRESHOLD_PHRASE
    candidates          = _get_ngrams(query_lower, kw_length)

    for candidate in candidates:
        if kw_length == 1:
            score = fuzz.ratio(keyword, candidate)
        else:
            score = fuzz.token_set_ratio(keyword, candidate)

        if score >= effective_threshold:
            return True

    return False


def validate_query(text: str) -> dict:

    text_lower = text.lower()
    matched    = {}
    missing    = []

    for key, keywords in VALIDATION_KEYWORDS.items():
        exact_found = []
        fuzzy_found = []

        for kw in keywords:
            kw_lower = kw.lower()
            if kw_lower in text_lower:
                # Lapis 1: exact match
                exact_found.append(kw_lower)
            elif kw_lower not in FUZZY_SKIP_KEYWORDS:
                # Lapis 2: fuzzy match — hanya untuk keyword yang tidak di-skip
                if _fuzzy_match_keyword(text_lower, kw_lower):
                    fuzzy_found.append(kw_lower)

        if exact_found or fuzzy_found:
            matched[key] = {"exact": exact_found, "fuzzy": fuzzy_found}
        else:
            missing.append(LABEL_MAP[key])

    all_covered = not missing
    has_fuzzy   = any(v["fuzzy"] for v in matched.values())

    if all_covered and not has_fuzzy:
        status = "valid"
    elif all_covered and has_fuzzy:
        status = "fixable"
    else:
        status = "invalid"

    return {
        "status":  status,   # "valid" | "fixable" | "invalid"
        "matched": matched,
        "missing": missing,
    }