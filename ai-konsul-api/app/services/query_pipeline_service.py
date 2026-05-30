from app.utils.text_preprocessing import preprocess_text
from app.services.keyword_fix_service import fix_query
from app.services.Quality_querycontrol import validate_query

class QueryPipelineService:

    def run(self, query: str):
        original_query = query
        cleaned_text   = preprocess_text(query)

        # Cek ketersediaan keyword skincare di dalam text
        query_check = validate_query(cleaned_text)
        status      = query_check["status"]

        # ---------------------------------------------------------
        # BARU: Tangani Pertanyaan OOD (Out of Domain) / Gak Nyambung
        # ---------------------------------------------------------
        if status == "out_of_context":
            # Lempar error spesifik. UI Blade yang kita perbarui sebelumnya 
            # akan otomatis memberikan kotak merah (styling warning) jika 
            # mendeteksi kata "Topik Tidak Dikenali" atau "Peringatan".
            raise ValueError(
                "⚠️ Topik Tidak Dikenali: SkinQuo dirancang khusus untuk menganalisis "
                "kondisi kulit dan merekomendasikan skincare. Tolong ceritakan masalah kulitmu saja, ya!"
            )

        # Tangani status invalid murni (jika ada logic invalid lain)
        if status == "invalid":
            return {
                "cleaned_text":   cleaned_text,
                "status":         "invalid",
                "missing_points": query_check.get("missing", []),
                "matched_points": query_check.get("matched", {}),
                "extracted_products": [],
                "extracted_concerns": [],
                "extracted_constraints": [],
                "query_fixing":   None,
            }

        # Handle "fixable" vs "valid" logic safely
        if status == "fixable":
            fix_result = fix_query(
                original_query   = original_query,
                cleaned_text     = cleaned_text,
                fixable_keywords = query_check["fixable_keywords"],
            )
            fixed_raw_query = fix_result["fixed_query"] or original_query
            final_cleaned_text = preprocess_text(fixed_raw_query)
            
            # Re-validasi dengan teks bersih
            query_check = validate_query(final_cleaned_text)
            current_status = "fixable"
        else:
            final_cleaned_text = cleaned_text
            fix_result = None
            current_status = "valid"

        # Ekstraksi yang AMAN dan JELAS
        matched = query_check["matched"]
        
        # Ekstrak jenis produk
        product_exact = matched.get("product", {}).get("exact", [])
        
        # ─── PERBAIKAN 3b: LOGIKA FILTERING TAG KONDISI KULIT ───
        problem_exact = matched.get("problem", {}).get("exact", [])
        skin_type_exact = matched.get("skin_type", {}).get("exact", [])
        raw_concerns = problem_exact + skin_type_exact
        
        # 1. Hapus kata yang terlalu umum dan tidak punya makna jika berdiri sendiri
        ignore_words = {"kulit", "skin", "semua jenis kulit", "all skin type"}
        raw_concerns = [c for c in raw_concerns if c not in ignore_words]
        
        # 2. Hapus kata (subset) yang sudah terwakili oleh frasa yang lebih spesifik
        # Contoh: Jika ada "kulit berminyak", maka kata "berminyak" dibuang.
        extracted_concerns = []
        for c1 in raw_concerns:
            is_subset = False
            for c2 in raw_concerns:
                # Jika c1 adalah bagian dari c2 (contoh: "jerawat" bagian dari "bekas jerawat")
                if c1 != c2 and c1 in c2:
                    is_subset = True
                    break
            if not is_subset and c1 not in extracted_concerns:
                extracted_concerns.append(c1)
        
        # Ekstrak bahan aktif
        constraint_exact = matched.get("constraint", {}).get("exact", [])

        return {
            "cleaned_text":   final_cleaned_text,
            "status":         current_status,
            "matched_points": matched,
            "extracted_products": product_exact,
            "extracted_concerns": extracted_concerns, # <-- Tag sekarang bersih (hanya 1 frasa yang paling tepat)
            "extracted_constraints": constraint_exact,
            "query_fixing":   fix_result,
        }
# from app.utils.text_preprocessing import preprocess_text
# from app.services.keyword_fix_service import fix_query
# from app.services.Quality_querycontrol import validate_query

# class QueryPipelineService:

#     def run(self, query: str):
#         original_query = query
#         cleaned_text   = preprocess_text(query)

#         query_check = validate_query(cleaned_text)
#         status      = query_check["status"]

#         if status == "invalid":
#             return {
#                 "cleaned_text":   cleaned_text,
#                 "status":         "invalid",
#                 "missing_points": query_check["missing"],
#                 "matched_points": query_check["matched"],
#                 "extracted_products": [],
#                 "extracted_concerns": [],
#                 "extracted_constraints": [],
#                 "query_fixing":   None,
#             }

#         # Handle "fixable" vs "valid" logic safely
#         if status == "fixable":
#             fix_result = fix_query(
#                 original_query   = original_query,
#                 cleaned_text     = cleaned_text,
#                 fixable_keywords = query_check["fixable_keywords"],
#             )
#             fixed_raw_query = fix_result["fixed_query"] or original_query
#             final_cleaned_text = preprocess_text(fixed_raw_query)
            
#             # Re-validasi dengan teks bersih
#             query_check = validate_query(final_cleaned_text)
#             current_status = "fixable"
#         else:
#             final_cleaned_text = cleaned_text
#             fix_result = None
#             current_status = "valid"

#         # Ekstraksi yang AMAN dan JELAS (Tidak ada lagi "categories" yang membingungkan)
#         matched = query_check["matched"]
        
#         # Ekstrak jenis produk (toner, serum, dll)
#         product_exact = matched.get("product", {}).get("exact", [])
        
#         # Ekstrak keluhan kulit (jerawat, berminyak, dll) dengan menggabungkan problem + skin_type
#         problem_exact = matched.get("problem", {}).get("exact", [])
#         skin_type_exact = matched.get("skin_type", {}).get("exact", [])
#         extracted_concerns = problem_exact + skin_type_exact
        
#         # Ekstrak bahan aktif (niacinamide, dll)
#         constraint_exact = matched.get("constraint", {}).get("exact", [])

#         return {
#             "cleaned_text":   final_cleaned_text,
#             "status":         current_status,
#             "matched_points": matched,
#             "extracted_products": product_exact,
#             "extracted_concerns": extracted_concerns,
#             "extracted_constraints": constraint_exact,
#             "query_fixing":   fix_result,
#         }


