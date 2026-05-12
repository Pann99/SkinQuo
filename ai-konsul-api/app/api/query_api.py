from fastapi import APIRouter

from app.schemas.query_schema import QueryRequest

from app.services.query_pipeline_service import QueryPipelineService
from app.services.Database_pipeline import run_pipeline
from app.services.Recommender import RecommenderService


router = APIRouter()

# =====================================================
# INIT SERVICES (RUN SEKALI SAAT STARTUP)
# =====================================================

print("[API] Loading database pipeline...")

df_clean = run_pipeline("data/Sociolla.csv")

print("[API] Initializing recommender engine...")

recommender = RecommenderService(df_clean)

query_pipeline = QueryPipelineService()


# =====================================================
# API ENDPOINT
# =====================================================

@router.post("/recommend")
def recommend_products(request: QueryRequest):

    # ==========================================
    # QUERY PIPELINE
    # ==========================================

    query_result = query_pipeline.run(
        request.query
    )

    # Jika query invalid
    if query_result["status"] == "invalid":
        return {
            "status": "invalid",
            "message": "Query tidak valid",
            "query_result": query_result,
            "recommendations": []
        }

    # ==========================================
    # CLEAN QUERY & EXTRACT CATEGORY
    # ==========================================

    cleaned_query = query_result["cleaned_text"]

    # Ambil kategori produk dari hasil Quality_querycontrol (misal: "toner")
    extracted_category = None
    matched_points = query_result.get("matched_points", {})
    
    if "product" in matched_points:
        # Utamakan exact match, fallback ke fuzzy match jika ada typo yang lolos
        if matched_points["product"].get("exact"):
            extracted_category = matched_points["product"]["exact"][0]
        elif matched_points["product"].get("fuzzy"):
            extracted_category = matched_points["product"]["fuzzy"][0]

    # ==========================================
    # RECOMMENDATION
    # ==========================================

    # Kirim extracted_category ke Recommender untuk di-filter secara mutlak
    recommendations = recommender.recommend(
        cleaned_query=cleaned_query,
        extracted_category=extracted_category,
        top_n=5
    )

    return {
        "status": "success",
        "original_query": request.query,
        "cleaned_query": cleaned_query,
        "extracted_category": extracted_category, # Ditambahkan untuk mempermudah pemantauan
        "recommendations": recommendations
    }