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

    search_terms = []
    if extracted_products:
        search_terms.extend(extracted_products)
    if extracted_concerns:
        search_terms.extend(extracted_concerns)

    # Membentuk string OR untuk query Supabase (contoh: "title.ilike.%toner%,title.ilike.%komedo%")
    or_conditions = []
    for term in search_terms:
        # Menggunakan ilike untuk pencarian case-insensitive yang fleksibel
        or_conditions.append(f"title.ilike.%{term}%")
    
    or_string = ",".join(or_conditions)

    try:
        # Eksekusi query ke tabel "articles" di Supabase
        response = (
            supabase.table("articles") 
            .select("*")
            .or_(or_string)
            .eq("is_published", True)  # Filter agar hanya artikel yang sudah dipublikasikan yang ditarik
            .limit(top_n)
            .execute()
        )
        
        # Transformasi dan mapping struktur data dari database ke format UI Blade Laravel
        formatted_articles = []
        for row in response.data:
            # 1. Membuat ringkasan (excerpt) otomatis dengan memotong isi penuh 'content'
            raw_content = row.get("content", "")
            excerpt = raw_content[:120] + "..." if len(raw_content) > 120 else raw_content

            # 2. Menghitung estimasi waktu baca secara dinamis (Rata-rata manusia membaca 200 kata/menit)
            word_count = len(raw_content.split())
            read_time_mins = max(1, word_count // 200)

            # 3. Membersihkan format penanggalan dari string 'created_at' bawaan Supabase
            created_raw = row.get("created_at", "")
            published_date = created_raw.split("T")[0] if "T" in created_raw else ""

            formatted_articles.append({
                "title": row.get("title", "Artikel Tanpa Judul"),
                "category": row.get("category", "Skincare Tips"),
                # Pastikan prefix /articles/ ini cocok dengan konfigurasi Route di web.php Laravel
                "url": f"/articles/{row.get('slug', '')}",  
                "cover_image": row.get("image_url", ""),    # Pemetaan dari kolom image_url
                "excerpt": excerpt,                         # Menggunakan hasil potongan content
                "published_at": published_date,             # Pemetaan tanggal pembuatan
                "read_time": f"{read_time_mins} min",       # Hasil estimasi waktu baca
                "tag": row.get("category", "")              # Fallback menggunakan category sebagai penanda tag
            })
        return formatted_articles
        
    except Exception as e:
        print(f"[API] Error ketika memuat artikel terkait: {e}")
        return []

# =====================================================
# API ENDPOINT
# =====================================================

@router.post("/refresh-keywords")
def refresh_keywords():
    try:
        keyword_manager.load_keywords_from_db()
        return {"status": "success", "message": "Keywords berhasil di-refresh dari database."}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.post("/recommend")
def recommend_products(request: QueryRequest):
    try:
        # Menjalankan pembersihan teks, pengecekan typo, dan ekstraksi kata kunci
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
                "query_fixing": query_result.get("query_fixing"),
                "related_articles": []  # Mengembalikan array kosong jika status pencarian tidak valid
            }

        # Mengambil hasil ekstraksi kata kunci yang telah diproses oleh pipeline
        cleaned_query = query_result["cleaned_text"]
        extracted_products = query_result.get("extracted_products", [])
        extracted_concerns = query_result.get("extracted_concerns", [])
        extracted_constraints = query_result.get("extracted_constraints", [])

        # 1. Menghitung kesamaan dan memproses rekomendasi produk (Content-Based + Boosting)
        recommendations = recommender.recommend(
            cleaned_query=cleaned_query,
            extracted_products=extracted_products,
            extracted_constraints=extracted_constraints,
            extracted_concerns=extracted_concerns,
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
            "extracted_concerns": extracted_concerns, 
            "extracted_constraints": extracted_constraints, 
            "recommendations": recommendations,
            "query_fixing": query_result.get("query_fixing"),
            "related_articles": related_articles  # Menyisipkan daftar artikel terkait ke dalam payload JSON
        }

    except ValueError as e:
        # Menangkap error spam, indikasi hacking, atau pertanyaan Out of Domain (OOD)
        raise HTTPException(status_code=422, detail=str(e))