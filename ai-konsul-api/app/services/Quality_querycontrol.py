from typing import Union

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
        "sensitive skin", "normal skin", "semua jenis kulit", "all skin type"
    ]
}

LABEL_MAP = {
    "product":    "[Product] Jenis produk",
    "problem":    "[Problem] Keluhan kulit",
    "constraint": "[Constraint] Kandungan aktif",
    "skin_type":  "[Area/Type] Jenis kulit"
}


def validate_query(text: str) -> dict:
    """
    Cek keberadaan 4 poin wajib dalam query:
      - [Product]    : Jenis produk skincare
      - [Problem]    : Keluhan / masalah kulit
      - [Constraint] : Kandungan aktif yang diinginkan
      - [Area/Type]  : Jenis kulit

    Returns:
        dict: {
            "is_valid" : bool,
            "matched"  : dict,       # { key: [keyword, ...] } yang ditemukan
            "missing"  : list[str],  # label poin yang tidak ditemukan
        }
    """
    text_lower = text.lower()
    matched    = {}
    missing    = []

    for key, keywords in VALIDATION_KEYWORDS.items():
        found = [kw for kw in keywords if kw in text_lower]
        if found:
            matched[key] = found
        else:
            missing.append(LABEL_MAP[key])

    return {
        "is_valid": not missing,
        "matched":  matched,
        "missing":  missing,
    }