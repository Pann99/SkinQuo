from app.core.supabase_client import supabase
import time
import httpx

class KeywordManager:
    _instance = None

    def __new__(cls):
        if cls._instance is None:
            cls._instance = super(KeywordManager, cls).__new__(cls)
            cls._instance.VALIDATION_KEYWORDS = {"product": [], "problem": [], "ingredient": [], "skin_type": []}
            cls._instance.PROTECTED_KEYWORDS = set()
            cls._instance.SORTED_PROTECTED = []
            cls._instance.PRECAUTION_MAP = {} 
            cls._instance.CANONICAL_MAP = {} 
        return cls._instance

    def load_keywords_from_db(self):
        print("[API] Menarik data keyword dan catatan medis dari Supabase...")
        
        max_retries = 3
        response = None
        
        for attempt in range(max_retries):
            try:
                if attempt > 0:
                    print(f"[API] Mencoba ulang menarik data keyword... (Percobaan {attempt + 1}/{max_retries})")
                
                response = supabase.table("validation_keywords").select("category, keyword, precaution_note").execute()
                break 
                
            except httpx.RemoteProtocolError as e:
                print(f"❌ [DATABASE ERROR] Koneksi terputus saat menarik keyword: {str(e)}")
                if attempt < max_retries - 1:
                    print("⏳ Menunggu 3 detik sebelum mencoba lagi...")
                    time.sleep(3)
                else:
                    print("🚨 [CRITICAL ERROR] Gagal terhubung ke Supabase setelah 3 percobaan.")
                    raise e
            except Exception as e:
                print(f"❌ [DATABASE ERROR] Terjadi kesalahan jaringan tak terduga (Keyword): {str(e)}")
                if attempt < max_retries - 1:
                    print("⏳ Menunggu 3 detik sebelum mencoba lagi...")
                    time.sleep(3)
                else:
                    raise e

        if not response or not hasattr(response, 'data') or not response.data:
            print("[API] Peringatan: Tabel validation_keywords kosong atau gagal ditarik!")
            return

        new_keywords = {"product": [], "problem": [], "ingredient": [], "skin_type": []}
        new_protected = set()
        new_precaution_map = {}
        new_canonical_map = {}

        # ─── IN-MEMORY CANONICAL LOOKUP (MENCEGAH REDUNDANSI "KERING, KULIT KERING") ───
        CANONICAL_LOOKUP = {
            # Jenis / Tipe Kulit
            "kulit berminyak": "Kulit Berminyak",
            "berminyak": "Kulit Berminyak",       # <-- [FIX] Menyatukan sifat tunggal
            "kulit kering": "Kulit Kering",
            "kering": "Kulit Kering",             # <-- [FIX] Menyatukan sifat tunggal
            "kulit sensitif": "Kulit Sensitif",
            "sensitif": "Kulit Sensitif",         # <-- [FIX] Menyatukan sifat tunggal
            "kulit kombinasi": "Kulit Kombinasi",
            "kombinasi": "Kulit Kombinasi",
            "kulit normal": "Kulit Normal",
            "normal": "Kulit Normal",
            
            # Keluhan / Masalah Kulit
            "jerawat": "Jerawat",
            "bruntusan": "Jerawat",
            "breakout": "Jerawat",
            "jerawatan": "Jerawat",
            "kulit kusam": "Kulit Kusam",
            "kusam": "Kulit Kusam",               # <-- [FIX]
            "flek hitam": "Flek Hitam",
            "komedo": "Komedo",
            "pori-pori besar": "Pori-Pori Besar",
            "kemerahan": "Kemerahan / Iritasi",
            "iritasi": "Kemerahan / Iritasi",
            "kerutan": "Kerutan / Penuaan",
            "penuaan": "Kerutan / Penuaan",
            "bekas jerawat": "Bekas Jerawat / Luka",
            "bekas luka": "Bekas Jerawat / Luka",
            
            # Kategori Jenis Produk
            "sabun cuci wajah": "Sabun Cuci Wajah",
            "sabun": "Sabun Cuci Wajah",
            "pelembab": "Pelembab (Moisturizer)",
            "krim mata": "Krim Mata (Eye Cream)"
        }

        for row in response.data:
            cat = row["category"]
            kw = row["keyword"].lower().strip()
            note = row.get("precaution_note")
            
            if cat in new_keywords:
                new_keywords[cat].append(kw)
                new_protected.add(kw)
            
            if note:
                new_precaution_map[kw] = note
            
            if kw in CANONICAL_LOOKUP:
                new_canonical_map[kw] = CANONICAL_LOOKUP[kw]
            else:
                new_canonical_map[kw] = kw.title()

        self.VALIDATION_KEYWORDS = new_keywords
        self.PROTECTED_KEYWORDS = new_protected
        self.SORTED_PROTECTED = sorted(list(new_protected), key=len, reverse=True)
        self.PRECAUTION_MAP = new_precaution_map
        self.CANONICAL_MAP = new_canonical_map
        
        print(f"[API] Berhasil memuat {len(new_protected)} keyword murni dan {len(new_precaution_map)} catatan medis.")

keyword_manager = KeywordManager()