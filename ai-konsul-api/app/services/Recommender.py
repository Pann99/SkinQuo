import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

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

    # Sesuaikan parameter agar menerima extracted_products alih-alih extracted_categories
    def recommend(self, cleaned_query: str, extracted_products: list = None, extracted_constraints: list = None, top_n: int = 5):
        if not cleaned_query.strip():
            return []

        query_vector = self.vectorizer.transform([cleaned_query])
        similarity_scores = cosine_similarity(query_vector, self.tfidf_matrix).flatten()

        if extracted_products:
            for product in extracted_products:
                if not product.strip():
                    continue
                category_mask = self.df["kategori_clean"].str.contains(
                    product, case=False, na=False, regex=False
                ).values
                similarity_scores[category_mask] += 0.25

        if extracted_constraints:
            for ingredient in extracted_constraints:
                if not ingredient.strip():
                    continue
                ingredient_mask = self.df["kandungan_clean"].str.contains(
                    ingredient, case=False, na=False, regex=False
                ).values
                similarity_scores[ingredient_mask] += 0.15 

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

            match_kategori = []
            if extracted_products:
                for cat in extracted_products:
                    if cat.lower() in kategori_produk.lower():
                        match_kategori.append(cat.title())
            
            if not match_kategori:
                match_kategori = [kategori_produk]

            match_kandungan = []
            if extracted_constraints:
                for ingredient in extracted_constraints:
                    if ingredient.lower() in kandungan_produk or ingredient.lower() in deskripsi_produk:
                        match_kandungan.append(ingredient.title())

            # UBAHAN UTAMA: Kembalikan JSON Metadata, bukan string kalimat panjang
            reasoning_meta = {
                "reason_code": "MATCHED_INGREDIENTS" if match_kandungan else "MATCHED_CATEGORY",
                "matched_categories": match_kategori,
                "matched_ingredients": match_kandungan
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
                "reasoning_meta": reasoning_meta # <-- Output baru yang bersih
            })

        return results


# import pandas as pd
# from sklearn.feature_extraction.text import TfidfVectorizer
# from sklearn.metrics.pairwise import cosine_similarity


# class RecommenderService:

#     def __init__(self, df_clean: pd.DataFrame):
#         self.df = df_clean.copy()

#         # VALIDASI: Pastikan kolom 'content_metadata' tersedia di dataframe.
#         if "content_metadata" not in self.df.columns:
#             raise ValueError(
#                 "[RECOMMENDER] ERROR: Kolom 'content_metadata' tidak ditemukan di dataframe. "
#                 "Pastikan pipeline preprocessing telah menghasilkan kolom tersebut sebelum "
#                 "menginisialisasi RecommenderService."
#             )

#         # Single Bag of Words dari metadata yang sudah bersih
#         corpus = self.df["content_metadata"].fillna("").str.strip()

#         # Konfigurasi TF-IDF
#         self.vectorizer = TfidfVectorizer(
#             min_df=1,
#             max_df=0.85,
#             sublinear_tf=True,
#             ngram_range=(1, 1)
#         )

#         self.tfidf_matrix = self.vectorizer.fit_transform(corpus)
#         print("[RECOMMENDER] READY")

#     def recommend(self, cleaned_query: str, extracted_categories: list = None, extracted_constraints: list = None, top_n: int = 5):
#             if not cleaned_query.strip():
#                 return []

#             # 1. Hitung Cosine Similarity 
#             query_vector = self.vectorizer.transform([cleaned_query])
#             similarity_scores = cosine_similarity(query_vector, self.tfidf_matrix).flatten()

#             # 2. UPDATE: MULTI-CATEGORY BOOSTING
#             if extracted_categories:
#                 for category in extracted_categories:
#                     if not category.strip():
#                         continue
#                     category_mask = self.df["kategori_clean"].str.contains(
#                         category, case=False, na=False, regex=False
#                     ).values
#                     similarity_scores[category_mask] += 0.25

#             # 3. SOFT SCORING (BOOSTING INGREDIENTS) 
#             if extracted_constraints:
#                 for ingredient in extracted_constraints:
#                     if not ingredient.strip():
#                         continue
#                     ingredient_mask = self.df["kandungan_clean"].str.contains(
#                         ingredient, case=False, na=False, regex=False
#                     ).values
#                     similarity_scores[ingredient_mask] += 0.15 

#             # 4. Ambil Top N
#             top_indices = similarity_scores.argsort()[::-1][:top_n]

#             results = []
#             for rank, idx in enumerate(top_indices, start=1):
#                 score = float(similarity_scores[idx])

#                 # Filter: Abaikan produk jika total skornya nol atau negatif
#                 if score <= 0:
#                     continue

#                 row = self.df.iloc[idx]
                
#                 # ==========================================
#                 # LOGIKA REASONING (IRISAN KATA KUNCI)
#                 # ==========================================
#                 kategori_produk = str(row.get("kategori_clean", "")).strip()
#                 kandungan_produk = str(row.get("kandungan_clean", "")).lower()
#                 deskripsi_produk = str(row.get("deskripsi_clean", "")).lower()

#                 # A. Cari irisan Kategori
#                 match_kategori = None
#                 if extracted_categories:
#                     for cat in extracted_categories:
#                         if cat.lower() in kategori_produk.lower():
#                             match_kategori = cat.title() # Format huruf kapital di awal
#                             break
                
#                 # Jika user tidak menyebut kategori, gunakan kategori asli produknya
#                 if not match_kategori:
#                     match_kategori = kategori_produk 

#                 # B. Cari irisan Kandungan (Constraints)
#                 match_kandungan = []
#                 if extracted_constraints:
#                     for ingredient in extracted_constraints:
#                         # Cek apakah bahan yang dicari ada di kandungan atau deskripsi
#                         if ingredient.lower() in kandungan_produk or ingredient.lower() in deskripsi_produk:
#                             match_kandungan.append(ingredient.title())

#                 # C. Rangkai Template Kalimat Alasan
#                 if match_kandungan:
#                     kandungan_teks = ", ".join(match_kandungan)
#                     reasoning = f"Kami merekomendasikan produk ini karena sesuai dengan pencarianmu untuk tipe {match_kategori}, dan terdeteksi memiliki kandungan {kandungan_teks} yang relevan dengan spesifikasimu."
#                 else:
#                     # Fallback kalimat jika tidak ada kandungan spesifik yang di-request
#                     reasoning = f"Produk ini sangat direkomendasikan karena sebagai {match_kategori}, profil fungsinya memiliki tingkat kecocokan yang sangat tinggi dengan keseluruhan kata kunci pencarianmu."

#                 # ==========================================

#                 results.append({
#                     "rank": rank,
#                     "product_name": row.get("nama_produk", ""),
#                     "brand": row.get("nama_brand", ""),
#                     "category": row.get("kategori_clean", ""),
#                     "description": row.get("deskripsi_clean", ""),
#                     "ingredients": row.get("kandungan_clean", ""),
#                     "image_url": row.get("image", ""),
#                     "link_produk": row.get("link_produk", ""),
#                     "similarity_score": round(score, 4),
#                     "alasan_rekomendasi": reasoning  # <-- OUTPUT BARU
#                 })

#             return results