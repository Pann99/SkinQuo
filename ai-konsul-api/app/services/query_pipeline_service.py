from app.utils.text_preprocessing import preprocess_text
from app.services.keyword_fix_service import fix_query
from app.services.Quality_querycontrol import validate_query


class QueryPipelineService:

    def run(self, query: str):
        # Step 1: Simpan original sebelum diproses
        original_query = query
        cleaned_text   = preprocess_text(query)

        # Step 2: Validate query quality (4 poin wajib)
        query_check = validate_query(cleaned_text)

        # Step 2a: Kirim ke fix_query jika tidak lolos validasi
        if not query_check["is_valid"]:
            query_fixing = fix_query(cleaned_text, original_query)
            return {
                "original_query": original_query,
                "cleaned_text":   cleaned_text,
                "is_valid":       False,
                "missing_points": query_check["missing"],
                "query_fixing":   query_fixing,
            }

        # Step 3: Matching data (lanjut jika valid)
        # ... matching logic here

        return {
            "original_query": original_query,
            "cleaned_text":   cleaned_text,
            "is_valid":       True,
            "matched_points": query_check["matched"],
            "query_fixing":   None,
        }