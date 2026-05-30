from app.core.supabase_client import supabase

class KeywordManager:
    _instance = None

    def __new__(cls):
        if cls._instance is None:
            cls._instance = super(KeywordManager, cls).__new__(cls)
            cls._instance.VALIDATION_KEYWORDS = {"product": [], "problem": [], "constraint": [], "skin_type": []}
            cls._instance.PROTECTED_KEYWORDS = set()
            cls._instance.SORTED_PROTECTED = []
            # ─── KUNCI AMAN: PASTIKAN ATRIBUT INI SUDAH TERDEKLARASI DI SINI ───
            cls._instance.PRECAUTION_MAP = {} 
        return cls._instance

    def load_keywords_from_db(self):
        print("[API] Menarik data keyword dan catatan medis dari Supabase...")
        response = supabase.table("validation_keywords").select("category, keyword, precaution_note").execute()
        
        if not response.data:
            print("[API] Peringatan: Tabel validation_keywords kosong!")
            return

        new_keywords = {"product": [], "problem": [], "constraint": [], "skin_type": []}
        new_protected = set()
        new_precaution_map = {}

        for row in response.data:
            cat = row["category"]
            kw = row["keyword"].lower().strip()
            note = row.get("precaution_note")
            
            if cat in new_keywords:
                new_keywords[cat].append(kw)
                new_protected.add(kw)
            
            # Daftarkan catatan medis formal ke dalam dictionary RAM
            if note:
                new_precaution_map[kw] = note

        self.VALIDATION_KEYWORDS = new_keywords
        self.PROTECTED_KEYWORDS = new_protected
        self.SORTED_PROTECTED = sorted(list(new_protected), key=len, reverse=True)
        self.PRECAUTION_MAP = new_precaution_map # <-- Diperbarui di sini setelah loop selesai
        
        print(f"[API] Berhasil memuat {len(new_protected)} keyword dan {len(new_precaution_map)} catatan medis.")

keyword_manager = KeywordManager()