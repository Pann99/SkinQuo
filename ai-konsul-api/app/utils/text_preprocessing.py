import re
from Sastrawi.StopWordRemover.StopWordRemoverFactory import StopWordRemoverFactory
from Sastrawi.Stemmer.StemmerFactory import StemmerFactory
from app.services.keyword_manager import keyword_manager  

# ── Initialize Sastrawi ──────────────────────────────────────────
stopword_factory = StopWordRemoverFactory()
stopword_remover  = stopword_factory.create_stop_word_remover()

stemmer_factory = StemmerFactory()
stemmer          = stemmer_factory.create_stemmer()

# Cache untuk mempercepat proses stemming kata non-keyword
STEM_CACHE = {}

# [TAMBAHAN: KAMUS SLANG]
SLANG_MAP = {
    "pas atas": "mengatasi",
    "atas": "mengatasi",
    "buat": "untuk",
    "bikin": "membuat",
    "ilang": "hilang",
    "ilangin": "menghilangkan",
    "nyari": "mencari",
    "pake": "pakai",
}

def _normalize_slang(text: str) -> str:
    """Mengubah bahasa santai/slang menjadi kata baku."""
    for slang, baku in SLANG_MAP.items():
        text = re.sub(rf'\b{slang}\b', baku, text)
    return text

# ── Internal helpers ─────────────────────────────────────────────

def _mask_protected(text: str) -> tuple[str, dict]:
    """Ganti protected keywords dengan placeholder __KW_i__ secara dinamis dari KeywordManager."""
    mapping = {}
    
    # ✔️ Ambil list keyword yang sudah diurutkan dari yang terpanjang secara dinamis dari RAM
    for i, keyword in enumerate(keyword_manager.SORTED_PROTECTED):
        placeholder = f"__KW_{i}__"
        if keyword in text:
            # Menggunakan regex word boundary (\b) agar hanya mengganti kata utuh
            text = re.sub(r'\b' + re.escape(keyword) + r'\b', placeholder, text)
            mapping[placeholder] = keyword
    return text, mapping


def _unmask_protected(text: str, mapping: dict) -> str:
    """Kembalikan placeholder ke keyword asli."""
    for placeholder, keyword in mapping.items():
        text = text.replace(placeholder, keyword)
    return text


def _safe_remove_stopwords(text: str) -> str:
    """Hapus stopword per kata untuk melindungi placeholder agar tidak rusak oleh Sastrawi."""
    words = text.split()
    filtered_words = [
        word for word in words 
        if "__KW_" in word or stopword_remover.remove(word) != ""
    ]
    return " ".join(filtered_words)


def _safe_stemming(text: str) -> str:
    """Proses stemming aman yang melewati token placeholder."""
    words = text.split()
    stemmed_words = []
    for word in words:
        if "__KW_" in word:
            stemmed_words.append(word)
        else:
            # Gunakan cache biar performa tracking kata tidak lambat
            if word not in STEM_CACHE:
                STEM_CACHE[word] = stemmer.stem(word)
            stemmed_words.append(STEM_CACHE[word])
    return " ".join(stemmed_words)


# ── Public API ───────────────────────────────────────────────────

def preprocess_text(text: str) -> str:
    """Pipeline pembersihan text utama untuk query konsultasi."""
    # 1. Lowercase dan whitespace
    text = text.lower().strip()

    # 2. Hapus karakter spesial 
    text = re.sub(r'[^a-z0-9\s_]', ' ', text)

    # 3. Lindungi keyword teknis sebelum diproses lebih lanjut
    text, mask_mapping = _mask_protected(text)

    # 4. Normalisasi spasi dobel
    text = re.sub(r'\s+', ' ', text).strip()

    # [PERBAIKAN: NORMALISASI SLANG SEBELUM SASTRAWI]
    text = _normalize_slang(text)

    # 5. Hapus stopword secara aman (word-by-word)
    text = _safe_remove_stopwords(text)

    # 6. Stemming secara aman (melewati placeholder)
    text = _safe_stemming(text)

    # 7. Kembalikan keyword asli dari placeholder
    text = _unmask_protected(text, mask_mapping)

    return text


def preprocess_ingredients(text: str) -> str:
    """Pipeline khusus untuk komposisi/ingredients data produk tanpa Sastrawi."""
    text = text.lower().strip()
    
    # Hapus karakter spesial (koma dll)
    text = re.sub(r'[^a-z0-9\s_]', ' ', text)
    
    # Tetap lindungi keyword penting (seperti "centella asiatica" atau "pha")
    text, mask_mapping = _mask_protected(text)
    text = re.sub(r'\s+', ' ', text).strip()
    
    # LANGSUNG UNMASK, lewati tahap Sastrawi stopword & stemming
    text = _unmask_protected(text, mask_mapping)
    
    return text