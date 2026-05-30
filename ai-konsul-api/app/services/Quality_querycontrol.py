"""
Quality_querycontrol.py
=======================
Validation Layer — decision maker, bukan correction engine.

Tanggung jawab layer ini:
    ✔  Exact matching keyword per kategori
    ✔  Fuzzy detection ringan — mendeteksi KEMUNGKINAN typo
    ✔  Menentukan status query: valid | fixable | out_of_context
    ✔  Menyiapkan fixable_keywords untuk dikirim ke keyword_fix_service
    ✔  Menjadi decision maker: layak difix atau tidak

Tidak boleh dilakukan di sini:
    ✘  Mengganti / mengoreksi kata
    ✘  Rekonstruksi query
    ✘  Semantic inference (MiniLM / embedding)
    ✘  Final correction apapun
"""

from __future__ import annotations
from rapidfuzz import fuzz
from app.services.keyword_manager import keyword_manager  # IMPORT INI

# ─────────────────────────────────────────────────────────────────────────────
# Keyword repository
# ─────────────────────────────────────────────────────────────────────────────

LABEL_MAP: dict[str, str] = {
    "product":    "[Product] Jenis produk",
    "problem":    "[Problem] Keluhan kulit",
    "constraint": "[Constraint] Kandungan aktif",
    "skin_type":  "[Area/Type] Jenis kulit",
}

# ─────────────────────────────────────────────────────────────────────────────
# Konfigurasi fuzzy detection
# ─────────────────────────────────────────────────────────────────────────────

DETECT_THRESHOLD_SINGLE: int = 78   # 1 kata 
DETECT_THRESHOLD_PHRASE: int = 85   # frasa   — ketat, cegah false positive frasa
# Keyword terlalu pendek/umum — skip fuzzy detection agar tidak noise
FUZZY_SKIP_KEYWORDS: frozenset[str] = frozenset({"kulit"})

# ─────────────────────────────────────────────────────────────────────────────
# N-gram helper
# ─────────────────────────────────────────────────────────────────────────────

def _get_ngrams(text: str, n: int) -> list[str]:
    """
    Sliding window n-gram per kata.
    Digunakan untuk mencocokkan multi-word keyword ke segmen query.
    """
    words = text.split()
    if len(words) < n:
        return [text]
    return [" ".join(words[i : i + n]) for i in range(len(words) - n + 1)]


# ─────────────────────────────────────────────────────────────────────────────
# Fuzzy detection — HANYA mendeteksi kandidat, tidak mengoreksi
# ─────────────────────────────────────────────────────────────────────────────

def _is_fuzzy_candidate(query_lower: str, keyword: str) -> bool:
    """
    Deteksi apakah ada kata di query yang KEMUNGKINAN adalah typo dari keyword.
    """
    if keyword in FUZZY_SKIP_KEYWORDS:
        return False

    kw_word_count = len(keyword.split())
    threshold     = DETECT_THRESHOLD_SINGLE if kw_word_count == 1 else DETECT_THRESHOLD_PHRASE
    candidates    = _get_ngrams(query_lower, kw_word_count)

    for candidate in candidates:
        scorer = fuzz.ratio if kw_word_count == 1 else fuzz.token_set_ratio
        if scorer(keyword, candidate) >= threshold:
            return True

    return False


# ─────────────────────────────────────────────────────────────────────────────
# Kategori matcher — exact + fuzzy detection per kategori
# ─────────────────────────────────────────────────────────────────────────────

def _match_category(
    text_lower: str,
    keywords:   list[str],
) -> tuple[list[str], list[str]]:
    """
    Cocokkan keyword satu kategori terhadap query.
    """
    exact_found:    list[str] = []
    fixable_found:  list[str] = []

    for kw in keywords:
        kw_lower = kw.lower()

        if kw_lower in text_lower:
            # Exact match — tidak perlu fuzzy check lagi
            exact_found.append(kw_lower)
        elif _is_fuzzy_candidate(text_lower, kw_lower):
            # Fuzzy candidate — ditandai untuk dikirim ke fixing layer
            fixable_found.append(kw_lower)

    return exact_found, fixable_found


# ─────────────────────────────────────────────────────────────────────────────
# Public API
# ─────────────────────────────────────────────────────────────────────────────

def validate_query(text: str) -> dict:
    """
    Validasi query terhadap keyword repository secara dinamis dari KeywordManager.
    """
    text_lower = text.lower()

    matched:           dict[str, dict] = {}
    missing:           list[str]       = []
    fixable_keywords:  dict[str, list] = {}

    # ✔️ MENGGUNAKAN KEYWORD MANAGER DI SINI
    for category, keywords in keyword_manager.VALIDATION_KEYWORDS.items():
        exact_found, fixable_found = _match_category(text_lower, keywords)

        if exact_found or fixable_found:
            matched[category] = {
                "exact":              exact_found,
                "fixable_candidates": fixable_found,
            }
            # Hanya sertakan dalam fixable_keywords jika ada kandidat aktual
            if fixable_found:
                fixable_keywords[category] = fixable_found
        else:
            missing.append(LABEL_MAP[category])

    # ── Status determination ──────────────────────────────────────────────────
    has_fixable   = bool(fixable_keywords)
    has_any_match = bool(matched)

    if not has_any_match:
        # SKENARIO 2: Tidak ada satupun kata dari kamus skincare yang cocok.
        # Ini berarti teks di luar konteks (Out of Context) atau hanya berisi kata sambung.
        status = "out_of_context"
    elif has_fixable:
        # Ada kata skincare, tapi ada yang terdeteksi typo, kirim ke fixing layer
        status = "fixable"
    else:
        # GERBANG DIBUKA: Asalkan ada minimal 1 kata relevan dan tidak ada typo, 
        # anggap VALID. Kita tidak lagi peduli apakah ada kategori yang 'missing'.
        status = "valid"

    return {
        "status":           status,
        "matched":          matched,
        "missing":          missing,
        "fixable_keywords": fixable_keywords,
    }

