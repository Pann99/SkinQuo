from fastapi import APIRouter, HTTPException
from app.schemas.query_schema import QueryRequest
from app.services.query_pipeline_service import QueryPipelineService
from app.services.Database_pipeline import run_pipeline_supabase
from app.services.Recommender import RecommenderService

# Murni deklarasi router tanpa membuat instance app baru
router = APIRouter()

# =====================================================
# INIT SERVICES DI TINGKAT GLOBAL
# =====================================================
print("[API] Loading database from Supabase...")
df_clean = run_pipeline_supabase() 

print("[API] Initializing recommender engine...")
recommender = RecommenderService(df_clean)

query_pipeline = QueryPipelineService()

# =====================================================
# API ENDPOINT
# =====================================================
@router.post("/recommend") # Ketika digabung ke main.py, ini menjadi /api/recommend
def recommend_products(request: QueryRequest):
    try:
        # Menggunakan penamaan yang konsisten: query_result
        query_result = query_pipeline.run(request.query)

        if query_result["status"] == "invalid":
            return {
                "status": "invalid",
                "message": "Query tidak valid atau berada di luar konteks perawatan kulit.",
                "missing_points": query_result.get("missing_points", []),
                "matched_points": query_result.get("matched_points", {}),
                "extracted_products": [],
                "extracted_concerns": [],
                "extracted_constraints": [],
                "recommendations": [],
                "query_fixing": query_result.get("query_fixing")
            }

        # Ambil data dari query_result dengan mapping kunci yang baru
        cleaned_query = query_result["cleaned_text"]
        extracted_products = query_result.get("extracted_products", [])
        extracted_concerns = query_result.get("extracted_concerns", [])
        extracted_constraints = query_result.get("extracted_constraints", [])

        # PERBAIKAN UTAMA: Menggunakan argumen dan variabel yang benar
        recommendations = recommender.recommend(
            cleaned_query=cleaned_query,
            extracted_products=extracted_products, # Sesuai dengan parameter baru di Recommender.py
            extracted_constraints=extracted_constraints,
            top_n=5
        )

        # Kembalikan response terstruktur untuk dikonsumsi oleh Laravel Controller
        return {
            "status": query_result["status"], # Bisa berupa 'valid' atau 'fixable'
            "original_query": request.query,
            "cleaned_query": cleaned_query,
            "extracted_products": extracted_products,
            "extracted_concerns": extracted_concerns, # Ikut dikirim agar Laravel bisa menyimpan ke 'skin_concern'
            "extracted_constraints": extracted_constraints, 
            "recommendations": recommendations,
            "query_fixing": query_result.get("query_fixing")
        }

    except ValueError as e:
        # MENANGKAP ERROR SPAM/HACKING/OOD:
        # ValueError yang dilempar dari query_schema.py atau query_pipeline_service.py
        # akan ditangkap di sini dan diubah menjadi HTTP 422 Unprocessable Entity
        raise HTTPException(status_code=422, detail=str(e))