import pandas as pd

from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity


class RecommenderService:

    def __init__(self, df_clean: pd.DataFrame):

        self.df = df_clean.copy()

        # PERBAIKAN 1: Hapus kategori_clean dari corpus, tambahkan nama_produk.
        # Fokuskan TF-IDF murni pada nama, deskripsi, dan kandungan bahan.
        self.df["tfidf_corpus"] = (
            self.df["nama_produk"].fillna("") + " " +
            self.df["deskripsi_clean"].fillna("") + " " +
            self.df["kandungan_clean"].fillna("")
        ).str.strip()

        # PERBAIKAN 2: Turunkan max_df menjadi 0.85
        # Ini akan menekan bobot kata yang muncul di lebih dari 85% dokumen (seperti kata "kulit" atau "wajah")
        self.vectorizer = TfidfVectorizer(
            min_df=1,
            max_df=0.85, 
            sublinear_tf=True,
            ngram_range=(1, 1)
        )

        self.tfidf_matrix = self.vectorizer.fit_transform(
            self.df["tfidf_corpus"]
        )

        print("[RECOMMENDER] READY")

    def recommend(self, cleaned_query: str, extracted_category: str = None, top_n: int = 5):
        if not cleaned_query.strip():
            return []

        # 1. HARD FILTERING: Filter dataframe jika user menyebutkan tipe produk
        if extracted_category:
            # Cari produk yang kategorinya mengandung kata yang diminta (misal: "Toner")
            mask = self.df['kategori_clean'].str.contains(extracted_category, case=False, na=False)
            filtered_indices = self.df.index[mask].tolist()
            
            # Jika ternyata tidak ada produk dengan kategori tersebut di database, batalkan filter (fallback ke semua data)
            if not filtered_indices:
                filtered_indices = self.df.index.tolist()
        else:
            filtered_indices = self.df.index.tolist()

        # 2. Ambil hanya baris matriks TF-IDF yang lolos filter
        filtered_matrix = self.tfidf_matrix[filtered_indices]

        # 3. Hitung Cosine Similarity HANYA pada produk yang lolos filter
        query_vector = self.vectorizer.transform([cleaned_query])
        similarity_scores = cosine_similarity(query_vector, filtered_matrix).flatten()

        # 4. Ambil Top N
        top_local_indices = similarity_scores.argsort()[::-1][:top_n]

        results = []
        for rank, local_idx in enumerate(top_local_indices, start=1):
            score = float(similarity_scores[local_idx])
            if score <= 0:
                continue

            # Petakan kembali indeks lokal ke indeks dataframe asli
            original_idx = filtered_indices[local_idx]
            row = self.df.iloc[original_idx]

            results.append({
                "rank": rank,
                "product_name": row.get("nama_produk", ""),
                "brand": row.get("nama_brand", ""),
                "category": row.get("kategori_clean", ""),
                "description": row.get("deskripsi_clean", ""),
                "ingredients": row.get("kandungan_clean", ""),
                "link_produk": row.get("link_produk", ""),
                "similarity_score": round(score, 4)
            })

        return results