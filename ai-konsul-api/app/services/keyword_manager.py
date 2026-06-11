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
        
        # =========================================================================
        # [NEW] SISTEM AUTO-RETRY UNTUK MENCEGAH "SERVER DISCONNECTED" SAAT STARTUP
        # =========================================================================
        max_retries = 3
        response = None
        
        for attempt in range(max_retries):
            try:
                if attempt > 0:
                    print(f"[API] Mencoba ulang menarik data keyword... (Percobaan {attempt + 1}/{max_retries})")
                
                response = supabase.table("validation_keywords").select("category, keyword, precaution_note").execute()
                break  # Jika berhasil, keluar dari loop
                
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
        # =========================================================================

        if not response or not hasattr(response, 'data') or not response.data:
            print("[API] Peringatan: Tabel validation_keywords kosong atau gagal ditarik!")
            return

        new_keywords = {"product": [], "problem": [], "ingredient": [], "skin_type": []}
        new_protected = set()
        new_precaution_map = {}
        new_canonical_map = {}

        # ─── IN-MEMORY CANONICAL LOOKUP (DISEDERHANAKAN) ───
        # Karena AI Semantic (MiniLM) sudah mengambil alih pencocokan makna kata
        # Dictionary ini sekarang HANYA bertugas memastikan format teks rapi saat dicetak ke UI.
        CANONICAL_LOOKUP = {
            # Jenis / Tipe Kulit
            "kulit berminyak": "Kulit Berminyak",
            "kulit kering": "Kulit Kering",
            "kulit sensitif": "Kulit Sensitif",
            "kulit kombinasi": "Kulit Kombinasi",
            "kulit normal": "Kulit Normal",
            
            # Keluhan / Masalah Kulit
            "jerawat": "Jerawat",
            "kulit kusam": "Kulit Kusam",
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
            "toner": "Toner",
            "serum": "Serum",
            "pelembab": "Pelembab (Moisturizer)",
            "sunscreen": "Sunscreen",
            "masker wajah": "Masker Wajah",
            "masker": "Masker Wajah",
            "eksfoliator": "Eksfoliator",
            "eksfoliasi": "Eksfoliator",
            "krim mata": "Krim Mata (Eye Cream)",
            "micellar water": "Micellar Water"
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
            
            # Lakukan pencocokan otomatis ke teks formal baku
            if kw in CANONICAL_LOOKUP:
                new_canonical_map[kw] = CANONICAL_LOOKUP[kw]
            else:
                new_canonical_map[kw] = kw.title()

        self.VALIDATION_KEYWORDS = new_keywords
        self.PROTECTED_KEYWORDS = new_protected
        self.SORTED_PROTECTED = sorted(list(new_protected), key=len, reverse=True)
        self.PRECAUTION_MAP = new_precaution_map
        self.CANONICAL_MAP = new_canonical_map
        
        print(f"[API] Berhasil memuat {len(new_protected)} keyword murni dan {len(new_precaution_map)} catatan medis dari Supabase.")

keyword_manager = KeywordManager()