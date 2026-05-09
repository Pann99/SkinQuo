import re
from sentence_transformers import SentenceTransformer, util
from app.services.Quality_querycontrol import validate_query, VALIDATION_KEYWORDS

model = SentenceTransformer("paraphrase-multilingual-MiniLM-L12-v2")

SIMILARITY_THRESHOLD = 0.85

LABEL_TO_KEY = {
    "[Product] Jenis produk":       "product",
    "[Problem] Keluhan kulit":      "problem",
    "[Constraint] Kandungan aktif": "constraint",
    "[Area/Type] Jenis kulit":      "skin_type"
}


def _print_fix_result(fix_result: dict, still_missing: list, fixed_query: str | None) -> None:
    print("\n── After Query Fixing ──────────────────────────────")

    if fix_result:
        print("✔ Kata dinormalisasi:")
        for label, info in fix_result.items():
            print(f"   {label}")
            print(f"   '{info['kata_asli']}' → '{info['kata_baku']}'  (score: {info['score']})")

    if still_missing:
        print("✘ Tidak ditemukan (harus dilengkapi user):")
        for label in still_missing:
            print(f"   · {label}")

    # Fixed query hanya tampil kalau benar-benar selesai
    if fixed_query:
        print(f"\n   Fixed query : \"{fixed_query}\"")

    status = "FIXED ✔" if not still_missing else "INCOMPLETE ✘"
    print(f"   Status      : {status}")
    print("────────────────────────────────────────────────────\n")


def fix_query(cleaned_text: str, original_query: str) -> dict:
    validated = validate_query(cleaned_text)

    if validated["is_valid"]:
        print("\n── After Query Fixing ──────────────────────────────")
        print("   Query sudah valid, tidak perlu fixing.")
        print("────────────────────────────────────────────────────\n")
        return {
            "is_fixable":    True,
            "fix_result":    {},
            "still_missing": [],
            "fixed_query":   original_query,
        }

    query_words   = cleaned_text.split()
    fix_result    = {}
    still_missing = []

    for label in validated["missing"]:
        category     = LABEL_TO_KEY.get(label)
        keyword_pool = VALIDATION_KEYWORDS.get(category, [])

        word_embeddings    = model.encode(query_words,   convert_to_tensor=True)
        keyword_embeddings = model.encode(keyword_pool,  convert_to_tensor=True)

        scores    = util.cos_sim(word_embeddings, keyword_embeddings)
        max_score = scores.max().item()
        max_idx   = scores.argmax()
        word_idx  = max_idx // len(keyword_pool)
        kw_idx    = max_idx %  len(keyword_pool)

        if max_score >= SIMILARITY_THRESHOLD:
            fix_result[label] = {
                "kata_asli": query_words[word_idx],
                "kata_baku": keyword_pool[kw_idx],
                "score":     round(max_score, 4)
            }
        else:
            still_missing.append(label)

    # ── Gerbang utama: fixed_query hanya dibangun jika SEMUA kategori berhasil ──
    if still_missing:
        # Ada yang tidak bisa di-fix → tolak, fixed_query tidak dibangun
        _print_fix_result(fix_result, still_missing, fixed_query=None)
        return {
            "is_fixable":    False,
            "fix_result":    fix_result,
            "still_missing": still_missing,
            "fixed_query":   None,          # ← eksplisit null
        }

    # Semua berhasil → bangun fixed_query dari original
    fixed_query = original_query.lower()
    for info in fix_result.values():
        fixed_query = re.sub(
            rf'\b{re.escape(info["kata_asli"])}\b',
            info["kata_baku"],
            fixed_query
        )

    _print_fix_result(fix_result, still_missing, fixed_query)
    return {
        "is_fixable":    True,
        "fix_result":    fix_result,
        "still_missing": [],
        "fixed_query":   fixed_query,       # ← hanya ada di sini
    }