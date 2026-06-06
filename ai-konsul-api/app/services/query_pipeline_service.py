from app.utils.text_preprocessing import preprocess_text
from app.services.keyword_fix_service import fix_query
from app.services.Quality_querycontrol import validate_query

class QueryPipelineService:

    def run(self, query: str):
        original_query = query
        cleaned_text   = preprocess_text(query)

        # =====================================================================
        # 1. LAYER VALIDASI (Merespons Kekuatan #1: Natural Language)
        # =====================================================================
        query_check = validate_query(cleaned_text)
        status      = query_check["status"]

        # Tangani Pertanyaan OOD (Out of Domain) agar tidak terjadi halusinasi
        if status == "out_of_context":
            raise ValueError(
                "⚠️ Topik Tidak Dikenali: SkinQuo dirancang khusus untuk menganalisis "
                "kondisi kulit dan merekomendasikan skincare. Tolong ceritakan masalah kulitmu saja, ya!"
            )

        if status == "invalid":
            return {
                "cleaned_text":   cleaned_text,
                "status":         "invalid",
                "missing_points": query_check.get("missing", []),
                "matched_points": query_check.get("matched", {})
            }

        # =====================================================================
        # 2. LAYER KOREKSI (Merespons Kekuatan #2 & #3: Typo & Semantic MiniLM)
        # =====================================================================
        if status == "fixable":
            fixable_kws = query_check.get("fixable_keywords", {})
            
            # Memanggil RapidFuzz dan MiniLM
            correction = fix_query(original_query, cleaned_text, fixable_kws)
            
            if correction["is_fixable"]:
                fixed_raw_query = correction["fixed_query"]
                final_cleaned_text = preprocess_text(fixed_raw_query)
                
                # Re-validasi dengan teks yang sudah bersih dari typo/bahasa asing
                query_check = validate_query(final_cleaned_text)
                current_status = "fixable"
                fix_result = correction["fix_result"]
            else:
                final_cleaned_text = cleaned_text
                current_status = "valid"
                fix_result = None
        else:
            final_cleaned_text = cleaned_text
            current_status = "valid"
            fix_result = None

        # =====================================================================
        # 3. LAYER EKSTRAKSI (Merespons Kekuatan #4 & #5: Multiple Preference)
        # =====================================================================
        matched = query_check.get("matched", {})
        
        # Ekstrak Kategori Produk (Bisa lebih dari satu, misal: Toner, Serum)
        product_exact = matched.get("product", {}).get("exact", [])
        
        # Ekstrak Keluhan & Tipe Kulit dan digabungkan menjadi Multiple Concern
        problem_exact = matched.get("problem", {}).get("exact", [])
        skin_type_exact = matched.get("skin_type", {}).get("exact", [])
        extracted_concerns = list(set(problem_exact + skin_type_exact))
        
        # Ekstrak Prioritas Bahan / Constraint (Bisa lebih dari satu, misal: Niacinamide, AHA)
        constraint_exact = matched.get("constraint", {}).get("exact", [])

        # (Opsional) Ekstrak Area Wajah jika Anda menaruhnya di kamus database
        face_area_exact = matched.get("face_area", {}).get("exact", [])

        return {
            "cleaned_text":          final_cleaned_text,
            "status":                current_status,
            "matched_points":        matched,
            "extracted_products":    product_exact,
            "extracted_concerns":    extracted_concerns,
            "extracted_constraints": constraint_exact,
            "extracted_face_area":   face_area_exact,
            "query_fixing":          fix_result,
        }