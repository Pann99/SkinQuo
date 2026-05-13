import re
from rapidfuzz import fuzz, process
from sentence_transformers import SentenceTransformer, util
from app.services.Quality_querycontrol import validate_query

model = SentenceTransformer("paraphrase-multilingual-MiniLM-L12-v2")

# Threshold string distance — cari kata typo di query
FUZZY_TYPO_THRESHOLD  = 72   # rapidfuzz ratio: cocok untuk typo karakter
# Threshold semantic — fallback jika string distance gagal
SEMANTIC_THRESHOLD    = 0.80

LABEL_TO_KEY = {
    "[Product] Jenis produk":       "product",
    "[Problem] Keluhan kulit":      "problem",
    "[Constraint] Kandungan aktif": "constraint",
    "[Area/Type] Jenis kulit":      "skin_type"
}


def _find_typo_word(query_words: list[str], kw_baku: str) -> tuple[str, float, str] | None:
    # Lapis 1: String distance — prioritas utama untuk typo
    best = process.extractOne(
        kw_baku,
        query_words,
        scorer=fuzz.ratio,
        score_cutoff=FUZZY_TYPO_THRESHOLD,
    )
    if best:
        kata_asli, score, _ = best
        if kata_asli != kw_baku:
            return kata_asli, round(score / 100, 4), "string_distance"

    # Lapis 2: Semantic similarity — fallback
    kw_embedding   = model.encode([kw_baku],    convert_to_tensor=True)
    word_embedding = model.encode(query_words,  convert_to_tensor=True)
    scores         = util.cos_sim(kw_embedding, word_embedding)[0]

    best_idx   = scores.argmax().item()
    best_score = scores[best_idx].item()

    if best_score >= SEMANTIC_THRESHOLD:
        kata_asli = query_words[best_idx]
        if kata_asli != kw_baku:
            return kata_asli, round(best_score, 4), "semantic"

    return None


def _print_fix_result(fix_result: dict, fixed_query: str | None) -> None:
    print("\n── After Query Fixing ──────────────────────────────")

    if fix_result:
        print("✔ Kata dinormalisasi:")
        for label, info in fix_result.items():
            print(f"   {label}")
            print(f"   '{info['kata_asli']}' → '{info['kata_baku']}'  "
                  f"(score: {info['score']}, metode: {info['metode']})")

    if fixed_query:
        print(f"\n   Fixed query : \"{fixed_query}\"")

    status = "FIXED ✔" if fixed_query else "INCOMPLETE ✘"
    print(f"   Status      : {status}")
    print("────────────────────────────────────────────────────\n")


def fix_query(cleaned_text: str, original_query: str) -> dict:
    validated   = validate_query(cleaned_text)
    query_words = cleaned_text.split()
    fix_result  = {}

    for key, match_data in validated["matched"].items():
        fuzzy_hits = match_data.get("fuzzy", [])
        if not fuzzy_hits:
            continue

        label = next((k for k, v in LABEL_TO_KEY.items() if v == key), None)
        if not label:
            continue

        for kw_baku in fuzzy_hits:
            result = _find_typo_word(query_words, kw_baku)
            if result:
                kata_asli, score, metode = result
                fix_result[f"{label}::{kw_baku}"] = {
                    "kata_asli": kata_asli,
                    "kata_baku": kw_baku,
                    "score":     score,
                    "metode":    metode,
                }

    if not fix_result:
        _print_fix_result(fix_result, fixed_query=None)
        return {
            "is_fixable":  False,
            "fix_result":  {},
            "fixed_query": None,
        }

    fixed_query = original_query.lower()
    for info in fix_result.values():
        fixed_query = re.sub(
            rf'\b{re.escape(info["kata_asli"])}\b',
            info["kata_baku"],
            fixed_query,
        )

    # ──────────────────────────────────────────────────────────────────
    # PERBAIKAN: DEDUPLIKASI KATA GANDA (Contoh: "acid acid" -> "acid")
    # ──────────────────────────────────────────────────────────────────
    
    # 1. Menghapus kata identik yang berurutan (misal: "acid acid")
    fixed_query = re.sub(r'\b(\w+)(?:\s+\1\b)+', r'\1', fixed_query, flags=re.IGNORECASE)
    
    # 2. Menghapus kata baku yang mungkin bertabrakan (misal: "kulit" + "kulit berminyak")
    # Kita pecah dan gabungkan kembali untuk membuang duplikat unik dalam urutan yang sama
    words = fixed_query.split()
    seen = set()
    result_words = []
    for w in words:
        # Kita simpan kata yang sudah ada, tapi jika kata tersebut bagian dari keyword, 
        # kita biarkan logic regex di atas yang menangani frasa.
        # Untuk pembersihan sederhana, join spasi sudah cukup membantu.
        if w not in seen:
            result_words.append(w)
            seen.add(w)

    fixed_query = " ".join(result_words)
    # ──────────────────────────────────────────────────────────────────

    _print_fix_result(fix_result, fixed_query)
    return {
        "is_fixable":  True,
        "fix_result":  fix_result,
        "fixed_query": fixed_query,
    }


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