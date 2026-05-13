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

        # 1. Hitung Cosine Similarity terhadap SELURUH dataset (bukan filtered_matrix)
        query_vector = self.vectorizer.transform([cleaned_query])
        similarity_scores = cosine_similarity(query_vector, self.tfidf_matrix).flatten()

        # 2. SOFT SCORING (BOOSTING): Memberikan penekanan pada kategori
        if extracted_category:
            # Buat mask untuk produk yang kategorinya mengandung kata kunci tersebut
            category_mask = self.df['kategori_clean'].str.contains(extracted_category, case=False, na=False).values
            
            # Berikan bonus skor (Boosting)
            # produk ke atas tanpa merusak distribusi skor similarity asli.
            similarity_scores[category_mask] += 0.25

        # 3. Ambil Top N berdasarkan skor yang sudah di-boost
        top_indices = similarity_scores.argsort()[::-1][:top_n]

        results = []
        for rank, idx in enumerate(top_indices, start=1):
            score = float(similarity_scores[idx])
            
            # Tetap gunakan threshold agar tidak menampilkan hasil yang sama sekali tidak relevan
            if score <= 0:
                continue

            row = self.df.iloc[idx]

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