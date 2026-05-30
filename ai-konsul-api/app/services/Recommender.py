import pandas as pd
import re
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
from app.services.keyword_manager import keyword_manager 

class RecommenderService:

    def __init__(self, df_clean: pd.DataFrame):
        self.df = df_clean.copy()

        if "content_metadata" not in self.df.columns:
            raise ValueError(
                "[RECOMMENDER] ERROR: Kolom 'content_metadata' tidak ditemukan di dataframe."
            )

        corpus = self.df["content_metadata"].fillna("").str.strip()
        self.vectorizer = TfidfVectorizer(
            min_df=1,
            max_df=0.85,
            sublinear_tf=True,
            ngram_range=(1, 1)
        )
        self.tfidf_matrix = self.vectorizer.fit_transform(corpus)
        print("[RECOMMENDER] READY")

    def recommend(self, cleaned_query: str, extracted_products: list = None, extracted_constraints: list = None, extracted_concerns: list = None, top_n: int = 5):
        if not cleaned_query.strip():
            return []

        # 1. Hitung dasar nilai Cosine Similarity
        query_vector = self.vectorizer.transform([cleaned_query])
        similarity_scores = cosine_similarity(query_vector, self.tfidf_matrix).flatten()

        # 2. Boosting Skor berdasarkan Jenis Produk
        if extracted_products:
            for product in extracted_products:
                if not product.strip():
                    continue
                category_mask = self.df["kategori_clean"].str.contains(
                    product, case=False, na=False, regex=False
                ).values
                similarity_scores[category_mask] += 0.25

        # 3. Boosting Skor berdasarkan Kandungan Aktif (Constraints) -> REGEX FLEKSIBEL
        if extracted_constraints:
            for ingredient in extracted_constraints:
                if not ingredient.strip():
                    continue
                
                # Ambil token inti (kata pertama) agar lebih fleksibel 
                # (misal: "Centella Asiatica Extract" -> "Centella")
                core_token = ingredient.strip().split()[0]
                
                # Gunakan regex=True dan batasan kata (\b) untuk pencarian parsial yang aman
                ingredient_mask = self.df["kandungan_clean"].str.contains(
                    rf"\b{re.escape(core_token)}", case=False, na=False, regex=True
                ).values
                similarity_scores[ingredient_mask] += 0.15 

        # 4. Boosting Skor berdasarkan Keluhan Kulit (Concerns) -> BOBOT BARU
        if extracted_concerns:
            for concern in extracted_concerns:
                if not concern.strip():
                    continue
                
                # Cek di dalam content_metadata karena keluhan bisa disebut di deskripsi atau nama
                concern_mask = self.df["content_metadata"].str.contains(
                    concern, case=False, na=False, regex=False
                ).values
                similarity_scores[concern_mask] += 0.10

        # 5. Ambil Top N indeks produk dengan skor tertinggi
        top_indices = similarity_scores.argsort()[::-1][:top_n]

        results = []
        for rank, idx in enumerate(top_indices, start=1):
            score = float(similarity_scores[idx])

            if score <= 0:
                continue

            row = self.df.iloc[idx]
            
            kategori_produk = str(row.get("kategori_clean", "")).strip()
            kandungan_produk = str(row.get("kandungan_clean", "")).lower()
            deskripsi_produk = str(row.get("deskripsi_clean", "")).lower()

            # A. Cari irisan kategori untuk display
            match_kategori = []
            if extracted_products:
                for cat in extracted_products:
                    if cat.lower() in kategori_produk.lower():
                        match_kategori.append(cat.title())
            
            if not match_kategori:
                match_kategori = [kategori_produk]

            # B. Cari irisan kandungan aktif untuk display
            match_kandungan = []
            if extracted_constraints:
                for ingredient in extracted_constraints:
                    # Pencarian display ini juga bisa dibantu token jika ingin konsisten,
                    # tapi menggunakan exact string aman untuk keperluan display teks utuh
                    if ingredient.lower() in kandungan_produk or ingredient.lower() in deskripsi_produk:
                        match_kandungan.append(ingredient.title())

            # ─── LOGIKA REASONING / ARGUMENTASI PAKAR DINAMIS ───
            concerns_text = ", ".join([c.title() for c in extracted_concerns]) if extracted_concerns else "kebutuhan kulitmu"
            ingredients_text = ", ".join(match_kandungan) if match_kandungan else "bahan aktif formulasi khusus"
            kategori_text = match_kategori[0] if match_kategori else "Produk"
            
            if match_kandungan and extracted_concerns:
                reasoning_text = f"Sesuai dengan pencarianmu, {kategori_text} ini diformulasikan khusus dengan {ingredients_text} yang terbukti secara klinis sangat efektif untuk merawat kondisi {concerns_text}."
            elif extracted_concerns:
                reasoning_text = f"Berdasarkan hasil analisis, profil kandungan pada {kategori_text} ini memiliki tingkat kecocokan yang tinggi untuk membantu mengatasi masalah {concerns_text}."
            elif match_kandungan:
                reasoning_text = f"Sesuai preferensimu, {kategori_text} ini memuat {ingredients_text} yang sangat direkomendasikan untuk memaksimalkan target perawatan spesifikmu."
            else:
                reasoning_text = f"{kategori_text} ini memiliki formulasi dengan tingkat kecocokan yang sangat tinggi dengan keseluruhan deskripsi kondisi yang kamu berikan."

            # ─── FITUR BARU: SELEKSI CATATAN MEDIS (PRECAUTION NOTES) ───
            active_precautions = []
            
            # Cek dari kandungan yang cocok di produk ini
            for ing in match_kandungan:
                ing_lower = ing.lower()
                if ing_lower in keyword_manager.PRECAUTION_MAP:
                    active_precautions.append(keyword_manager.PRECAUTION_MAP[ing_lower])
            
            # Cek dari prioritas bahan yang diminta user tapi mungkin tidak ada di produk
            if extracted_constraints:
                for con in extracted_constraints:
                    con_lower = con.lower()
                    if con_lower in keyword_manager.PRECAUTION_MAP:
                        active_precautions.append(keyword_manager.PRECAUTION_MAP[con_lower])
            
            # Hapus duplikat catatan
            active_precautions = list(set(active_precautions))

            # Bentuk JSON Metadata bersih
            reasoning_meta = {
                "reason_code": "MATCHED_INGREDIENTS" if match_kandungan else "MATCHED_CATEGORY",
                "matched_categories": match_kategori,
                "matched_ingredients": match_kandungan,
                "reasoning_text": reasoning_text,
                "precaution_notes": active_precautions 
            }

            results.append({
                "rank": rank,
                "product_name": row.get("nama_produk", ""),
                "brand": row.get("nama_brand", ""),
                "category": row.get("kategori_clean", ""),
                "description": row.get("deskripsi_clean", ""),
                "ingredients": row.get("kandungan_clean", ""),
                "image_url": row.get("image", ""),
                "link_produk": row.get("link_produk", ""),
                "similarity_score": round(score, 4),
                "reasoning_meta": reasoning_meta
            })

        return results