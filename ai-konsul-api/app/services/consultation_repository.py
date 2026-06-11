from app.core.supabase_client import supabase

class ConsultationRepository:
    
    @staticmethod
    def save_consultation(
        user_id: int, 
        raw_query: str, 
        nlp_result: dict, 
        recommendations: list,
        harga_max: int = None   # <--- [NEW] Tambahan parameter
    ) -> bool:
        """
        Menyimpan riwayat sesi konsultasi (Header) dan 5 hasil produk terbaik (Detail)
        ke dalam Supabase secara relasional untuk kebutuhan pengolahan data science.
        """
        try:
            extracted_concerns = (
                nlp_result.get("extracted_skin_types", []) + 
                nlp_result.get("extracted_problems", [])
            )
            
            # --- [UBAH BAGIAN INI] ---
            # SEBELUMNYA: 
            # extracted_ingredients = nlp_result.get("extracted_ingredients", [])
            
            # SESUDAHNYA (Ambil dari display_explainability yang murni belum diekspansi AI):
            extracted_ingredients = nlp_result.get("display_explainability", {}).get("Kandungan Aktif", [])
            # -------------------------

            cleaned_query = nlp_result.get("cleaned_text", "")

            # TAHAP 1: INSERT HEADER LAYER (Tabel consultations)
            header_data = {
                "user_id": user_id,
                "raw_query": raw_query,
                "cleaned_query": cleaned_query,
                "extracted_concerns": extracted_concerns,      
                "extracted_ingredients": extracted_ingredients, # Sekarang datanya murni!
                "user_budget": harga_max
            }
            
            header_response = supabase.table("consultations").insert(header_data).execute()
            
            if not header_response.data:
                print("[DATABASE] ERROR: Gagal melakukan insert ke tabel consultations.")
                return False
                
            # Ambil ID Relasi Utama yang digenerate oleh Postgres
            consultation_id = header_response.data[0]["id"]
            
            # =====================================================================
            # TAHAP 2: PREPARE BULK INSERT DETAIL LAYER (Tabel consultation_results)
            # =====================================================================
            details_to_insert = []
            
            for item in recommendations:
                details_to_insert.append({
                    "consultation_id": consultation_id, 
                    "product_id": int(item["product_id"]),
                    "rank_position": int(item["rank"]),
                    "similarity_score": float(item["similarity_score"]),
                    "reasoning_code": item["reasoning_meta"]["reason_code"]
                })
                
            # =====================================================================
            # TAHAP 3: EKSEKUSI BULK INSERT DETAIL
            # =====================================================================
            if details_to_insert:
                supabase.table("consultation_results").insert(details_to_insert).execute()
                
            print(f"[DATABASE] SUCCESS: Sesi Konsultasi ID #{consultation_id} berhasil direkam ke database.")
            return True
            
        except Exception as e:
            print(f"[DATABASE] CRITICAL ERROR pada simpan data relasional: {str(e)}")
            return False