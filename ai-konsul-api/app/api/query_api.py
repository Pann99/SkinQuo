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
        
        # [DIPERBAIKI] Mengambil Data String Murni dari Pipeline, bukan Dictionary dari matched_points
        extracted_products = query_result.get("extracted_products", [])
        extracted_ingredients = query_result.get("extracted_ingredients", [])
        extracted_skin_types = query_result.get("extracted_skin_types", [])
        extracted_problems = query_result.get("extracted_problems", [])

        # Recommender SAW + Enriched TF-IDF
        recommendations = recommender.recommend(
            cleaned_query=cleaned_query,
            extracted_products=extracted_products,
            extracted_ingredients=extracted_ingredients,  
            extracted_skin_types=extracted_skin_types,    
            extracted_problems=extracted_problems,  
            harga_max=request.harga_max,  
            top_n=5
        )

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