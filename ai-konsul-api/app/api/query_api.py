from fastapi import APIRouter, HTTPException
from app.schemas.query_schema import QueryRequest
from app.services.query_pipeline_service import QueryPipelineService
from app.services.Database_pipeline import run_pipeline_supabase
from app.services.Recommender import RecommenderService
from app.services.keyword_manager import keyword_manager
from app.core.supabase_client import supabase 

router = APIRouter()

# =====================================================
# INIT SERVICES DI TINGKAT GLOBAL
# =====================================================
print("[API] Loading database from Supabase...")
df_clean = run_pipeline_supabase() 

keyword_manager.load_keywords_from_db()

print("[API] Initializing recommender engine...")
recommender = RecommenderService(df_clean)

query_pipeline = QueryPipelineService()

# def fetch_related_articles(extracted_products: list, extracted_concerns: list, top_n: int = 4) -> list:
#     if not extracted_products and not extracted_concerns:
#         return []
        
#     try:
#         search_tokens = list(set(extracted_products + extracted_concerns))
#         safe_tokens = [f"'{token}'" if " " in token else token for token in search_tokens]
#         search_query = " | ".join(safe_tokens)
        
#         response = (
#             supabase
#             .table("educational_articles")
#             .select("id, title, category, content, image_url, created_at")
#             .textSearch("fts_content", search_query, config="english")
#             .limit(top_n)
#             .execute()
#         )
#         return response.data if response.data else []
#     except Exception as e:
#         print(f"[API Warning] Gagal mengambil artikel edukasi: {str(e)}")
#         return []

# [MODIFIKASI] Hapus parameter user_id. API AI sekarang murni memproses data (Tanpa Insert DB).
@router.post("/recommend")
def recommend_products(request: QueryRequest):
    try:
        query_result = query_pipeline.run(request.query)
        
        if query_result["status"] == "invalid":
            return {
                "status": "invalid",
                "original_query": request.query,
                "cleaned_query": query_result.get("cleaned_text", ""),
                "missing_points": query_result.get("missing_points", []),
                "matched_points": query_result.get("matched_points", {}),
                "recommendations": []
            }

        cleaned_query = query_result["cleaned_text"]
        extracted_products = query_result.get("extracted_products", [])
        extracted_ingredients = query_result.get("extracted_ingredients", [])
        
        matched_data = query_result.get("matched_points", {})
        extracted_skin_types = matched_data.get("skin_type", {}).get("exact", [])
        extracted_problems = matched_data.get("problem", {}).get("exact", [])
        
        # extracted_concerns = list(set(extracted_skin_types + extracted_problems))

        # Recommender SAW
        recommendations = recommender.recommend(
            cleaned_query=cleaned_query,
            extracted_products=extracted_products,
            extracted_ingredients=extracted_ingredients,  
            extracted_skin_types=extracted_skin_types,    
            extracted_problems=extracted_problems,  
            harga_max=request.harga_max,  
            top_n=5
        )

        # related_articles = fetch_related_articles(
        #     extracted_products=extracted_products, 
        #     extracted_concerns=extracted_concerns
        # )

        # Output Murni dikembalikan ke Laravel agar Laravel yang mengeksekusi Database
        return {
            "status":                 query_result["status"],  
            "original_query":         request.query,
            "cleaned_query":          cleaned_query,
            "extracted_products":     extracted_products,
            "extracted_skin_types":   extracted_skin_types,
            "extracted_problems":     extracted_problems, 
            "extracted_ingredients":  extracted_ingredients,
            "display_explainability": query_result.get("display_explainability", {}),
            "recommendations":        recommendations,
            "query_fixing":           query_result.get("query_fixing"),
        }

    except ValueError as e:
        raise HTTPException(status_code=422, detail=str(e))