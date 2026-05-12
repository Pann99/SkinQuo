from app.utils.text_preprocessing import preprocess_text
from app.services.keyword_fix_service import fix_query
from app.services.Quality_querycontrol import validate_query


class QueryPipelineService:

    def run(self, query: str):
        # Step 1: Preprocessing
        original_query = query
        cleaned_text   = preprocess_text(query)

        # Step 2: Validasi — gerbang pertama, cek query asli
        query_check = validate_query(cleaned_text)
        status      = query_check["status"]

        # Step 3: Routing berdasarkan status
        if status == "invalid":
            # Kategori wajib tidak lengkap — tolak, minta input ulang
            return {
                "cleaned_text":   cleaned_text,
                "status":         "invalid",
                "missing_points": query_check["missing"],
                "matched_points": query_check["matched"],
                "query_fixing":   None,
            }

        if status == "fixable":
            fix_result = fix_query(cleaned_text, original_query)
            final_text = fix_result["fixed_query"] or cleaned_text  # fallback jika fixing gagal

            query_check = validate_query(final_text)

            return {
                "cleaned_text":   final_text,
                "status":         "fixable",
                "matched_points": query_check["matched"],
                "query_fixing":   fix_result,   # kirim dict lengkap, bukan hanya string
    }
        # status == "valid" — bersih, langsung ke matching
        # Step 4: Matching logic
        # ... matching logic here
        return {
            "cleaned_text":   cleaned_text,
            "status":         "valid",
            "matched_points": query_check["matched"],
            "query_fixing":   None,
        }