from fastapi import APIRouter, HTTPException
from app.schemas.query_schema import QueryRequest
from app.services.query_pipeline_service import QueryPipelineService
from app.services.Database_pipeline import run_pipeline_supabase
from app.services.Recommender import RecommenderService
from app.services.keyword_manager import keyword_manager

# --- IMPORT KLIEN SUPABASE UNTUK TARIK ARTIKEL ---
from app.core.supabase_client import supabase 

router = APIRouter()

# =====================================================
# INIT SERVICES DI TINGKAT GLOBAL
# =====================================================
print("[API] Loading database from Supabase...")
df_clean = run_pipeline_supabase() 

# Memuat kamus keyword dari database sebelum pipeline lain berjalan
keyword_manager.load_keywords_from_db()

print("[API] Initializing recommender engine...")
recommender = RecommenderService(df_clean)

query_pipeline = QueryPipelineService()


# =====================================================
# HELPER FUNCTION: PENCARIAN ARTIKEL EDUKASI
# =====================================================
def fetch_related_articles(extracted_products: list, extracted_concerns: list, top_n: int = 4) -> list:
    """
    Mencari artikel edukasi di Supabase berdasarkan Kategori Produk atau Keluhan Kulit.
    Menggunakan logika OR agar artikel yang mengandung salah satu kata kunci tetap ditarik.
    """
    if not extracted_products and not extracted_concerns:
        return []
        
    try:
        # Gabungkan semua token pencarian untuk dicocokkan ke text search Supabase
        search_tokens = list(set(extracted_products + extracted_concerns))
        # Gabungkan token dengan separator ' | ' untuk sintaks textsearch postgres (OR logika)
        search_query = " | ".join(search_tokens)
        
        response = (
            supabase
            .table("educational_articles")
            .select("id, title, category, content, image_url, created_at")
            .textSearch("fts_content", search_query, config="english")
            .limit(top_n)
            .execute()
        )
        return response.data if response.data else []
    except Exception as e:
        print(f"[API Warning] Gagal mengambil artikel edukasi: {str(e)}")
        return []


# =====================================================
# ENDPOINT UTAMA: RECOMMENDATION SYSTEM
# =====================================================
@router.post("/recommend")
def recommend_products(request: QueryRequest):
    """
    Endpoint utama untuk memproses query natural language dari user, 
    melakukan normalisasi typo/semantik, mengekstrak entitas,
    dan menghitung ranking produk terbaik menggunakan 5 Kriteria SAW.
    """
    try:
        # Jalankan pipeline NLP (Cleaning -> Validasi -> Koreksi -> Ekstraksi)
        query_result = query_pipeline.run(request.query)
        
        # Jika status invalid (misal kurang keyword esensial), langsung return statusnya
        if query_result["status"] == "invalid":
            return {
                "status": "invalid",
                "original_query": request.query,
                "cleaned_query": query_result["cleaned_text"],
                "missing_points": query_result.get("missing_points", []),
                "matched_points": query_result.get("matched_points", {}),
                "recommendations": []
            }

        # Ambil hasil pembersihan teks dan ekstraksi entitas murni dari pipeline
        cleaned_query = query_result["cleaned_text"]
        extracted_products = query_result.get("extracted_products", [])
        extracted_ingredients = query_result.get("extracted_ingredients", [])
        
        # Penyesuaian ke komponen 5 Kriteria SAW:
        # Memisahkan kembali concerns menjadi skin_types dan problems jika disimpan terpisah,
        # atau mengambil data penamaan entitas murni dari matched points.
        matched_data = query_result.get("matched_points", {})
        extracted_skin_types = matched_data.get("skin_type", {}).get("exact", [])
        extracted_problems = matched_data.get("problem", {}).get("exact", [])
        
        # Fallback gabungan concern untuk pencarian artikel edukasi
        extracted_concerns = list(set(extracted_skin_types + extracted_problems))

        # 1. Menghitung kesamaan dan memproses rekomendasi produk (Content-Based + 5 Kriteria SAW)
        recommendations = recommender.recommend(
            cleaned_query=cleaned_query,
            extracted_products=extracted_products,
            extracted_ingredients=extracted_ingredients,  # <-- SINKRON ✔️
            extracted_skin_types=extracted_skin_types,    # <-- SINKRON ✔️
            extracted_problems=extracted_problems,        # <-- SINKRON ✔️
            top_n=5
        )

        # 2. Mencari artikel edukasi yang relevan di Supabase secara paralel
        related_articles = fetch_related_articles(
            extracted_products=extracted_products, 
            extracted_concerns=extracted_concerns
        )

        # 3. Mengirimkan paket response terstruktur dan lengkap menuju Laravel Controller
        return {
            "status": query_result["status"],  # Berupa status 'valid' atau 'fixable'
            "original_query": request.query,
            "cleaned_query": cleaned_query,
            "extracted_products": extracted_products,
            "extracted_skin_types": extracted_skin_types,
            "extracted_problems": extracted_problems, 
            "extracted_ingredients": extracted_ingredients,
            "recommendations": recommendations,
            "query_fixing": query_result.get("query_fixing"),
            "related_articles": related_articles  # Menyisipkan daftar artikel terkait ke dalam payload JSON
        }

    except ValueError as e:
        # Menangkap error spam, indikasi hacking, atau pertanyaan Out of Domain (OOD)
        raise HTTPException(status_code=422, detail=str(e))