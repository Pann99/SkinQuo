from app.core.supabase_client import supabase

class ConsultationRepository:
    
    @staticmethod
    def save_consultation(
        user_id: int, 
        raw_query: str, 
        nlp_result: dict, 
        recommendations: list,
        harga_max: int = None   
    ) -> bool:
        try:
            print("[DATABASE] 💾 Menyimpan histori konsultasi...")
            extracted_concerns = (
                nlp_result.get("extracted_skin_types", []) + 
                nlp_result.get("extracted_problems", [])
            )
            
            extracted_ingredients = nlp_result.get("extracted_ingredients", [])
            cleaned_query = nlp_result.get("cleaned_text", "")
            
            skin_concern_str = ", ".join(extracted_concerns) if extracted_concerns else "Tidak spesifik"
            ai_response_text = f"Sistem merekomendasikan {len(recommendations)} produk berdasarkan analisis profil wajah dan bahan aktif."

            header_data = {
                "user_id": user_id,
                "skin_concern": skin_concern_str,         
                "raw_query": raw_query,
                "cleaned_query": cleaned_query,
                "user_budget": harga_max,
                "ai_response": ai_response_text,          
                "extracted_concerns": extracted_concerns,      
                "extracted_ingredients": extracted_ingredients
            }
            
            header_response = supabase.table("consultations").insert(header_data).execute()
            
            if not header_response.data:
                print("❌ [DATABASE] ERROR: Gagal melakukan insert ke tabel consultations.")
                return False
                
            consultation_id = header_response.data[0]["id"]
            details_to_insert = []
            
            for item in recommendations:
                details_to_insert.append({
                    "consultation_id": consultation_id, 
                    "product_id": int(item["product_id"]),
                    "rank_position": int(item["rank"]),
                    "similarity_score": float(item["similarity_score"]),
                    "reasoning_code": item["reasoning_meta"]["reason_code"] 
                })
                
            if details_to_insert:
                supabase.table("consultation_results").insert(details_to_insert).execute()
                
            print(f"[DATABASE] ✅ SUCCESS: Sesi Konsultasi ID #{consultation_id} berhasil direkam ke database.")
            return True
            
        except Exception as e:
            print(f"🚨 [DATABASE] CRITICAL ERROR pada simpan data relasional: {str(e)}")
            return False