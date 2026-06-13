import pandas as pd
import re
import math
import numpy as np
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
from app.services.keyword_manager import keyword_manager 

class RecommenderService:

    def __init__(self, df_clean: pd.DataFrame):
        self.df = df_clean.copy()

        if "content_metadata" not in self.df.columns:
            raise ValueError(
                "[RECOMMENDER] ERROR: Kolom 'content_metadata' tidak ditemukan."
            )

        corpus = self.df["content_metadata"].fillna("").str.strip()
        self.vectorizer = TfidfVectorizer(
            min_df=1, max_df=0.85, sublinear_tf=True, ngram_range=(1, 1)
        )
        self.tfidf_matrix = self.vectorizer.fit_transform(corpus)
        
        # SAW Weights - 6 Criteria (Independent & Normalized)
        self.W_C1 = 0.30  # Cosine Similarity (Murni Free Text)
        self.W_C2 = 0.10  # Category Match
        self.W_C3 = 0.20  # Ingredient Match
        self.W_C4 = 0.15  # Skin Type Match
        self.W_C5 = 0.15  # Problem Match
        self.W_C6 = 0.15  # Price Threshold Weight
        
        print("[RECOMMENDER] READY (6 CRITERIA - MURNI SAW & CBF STANDAR)")

    def recommend(self, cleaned_query: str, extracted_products: list = None, 
                  extracted_ingredients: list = None, extracted_skin_types: list = None, 
                  extracted_problems: list = None, harga_max: float = None, top_n: int = 5):
        
        # =====================================================================
        # 1. SANITASI INPUT
        # =====================================================================
        def _sanitize_to_str(lst):
            if not lst: return []
            return [str(item.get("keyword", "")) if isinstance(item, dict) else str(item) for item in lst]

        extracted_products    = _sanitize_to_str(extracted_products)
        extracted_ingredients = _sanitize_to_str(extracted_ingredients)
        extracted_skin_types  = _sanitize_to_str(extracted_skin_types)
        extracted_problems    = _sanitize_to_str(extracted_problems)

        if not cleaned_query.strip():
            return []

        n_products = len(self.df)

        # =====================================================================
        # 2. KRITERIA C1 - TF-IDF COSINE SIMILARITY MURNI (Mencegah Redundansi)
        # =====================================================================
        # Perbaikan: Menggunakan cleaned_query asli tanpa injeksi token nlp
        # agar tidak terjadi tumpang tindih bobot nilai (double counting) dengan C2-C5
        query_vector = self.vectorizer.transform([cleaned_query])
        c1_matrix = cosine_similarity(query_vector, self.tfidf_matrix).flatten()

        if c1_matrix.max() > 0:
            c1_matrix = c1_matrix / c1_matrix.max()

        # =====================================================================
        # 3. MATRIKS KRITERIA HARD-FACT (C2 - C5)
        # =====================================================================
        c2_matrix = np.zeros(n_products)
        if extracted_products:
            for product in extracted_products:
                if not product.strip(): continue
                mask = self.df["kategori_clean"].str.contains(product, case=False, na=False, regex=False).values
                c2_matrix[mask] = 1.0

        c3_matrix = np.zeros(n_products)
        if extracted_ingredients:
            for ingredient in extracted_ingredients:
                if not ingredient.strip(): continue
                core_token = ingredient.strip().split()[0]
                mask = self.df["kandungan_clean"].str.contains(rf"\b{re.escape(core_token)}", case=False, na=False, regex=True).values
                c3_matrix[mask] = 1.0

        c4_matrix = np.zeros(n_products)
        if extracted_skin_types:
            for st in extracted_skin_types:
                if not st.strip(): continue
                mask = self.df["content_metadata"].str.contains(st, case=False, na=False, regex=False).values
                c4_matrix[mask] = 1.0
                
        c5_matrix = np.zeros(n_products)
        if extracted_problems:
            for prob in extracted_problems:
                if not prob.strip(): continue
                mask = self.df["content_metadata"].str.contains(prob, case=False, na=False, regex=False).values
                c5_matrix[mask] = 1.0

        # =====================================================================
        # 4. KRITERIA C6 - DYNAMIC PRICE THRESHOLD
        # =====================================================================
        c6_matrix = np.zeros(n_products)
        has_explicit_budget = harga_max is not None
        for idx in range(n_products):
            row = self.df.iloc[idx]
            p_max = float(row.get("harga_max_clean", 0))
            if p_max > 0:
                if has_explicit_budget and p_max <= harga_max:
                    c6_matrix[idx] = 1.0
                elif has_explicit_budget and p_max > harga_max:
                    c6_matrix[idx] = harga_max / p_max
                else:
                    c6_matrix[idx] = 0.5
            else:
                c6_matrix[idx] = 0.5

        # =====================================================================
        # 5. AGREGASI MATEMATIS SAW MURNI
        # =====================================================================
        saw_main_score = (
            (c1_matrix * self.W_C1) + (c2_matrix * self.W_C2) +
            (c3_matrix * self.W_C3) + (c4_matrix * self.W_C4) + (c5_matrix * self.W_C5)
        )
        final_scores = saw_main_score + (c6_matrix * self.W_C6)

        # =====================================================================
        # 6. PERANGKINGAN STANDAR SAW (Menghapus Overkill Logika Quota/Fallback)
        # =====================================================================
        # Perbaikan: Menghapus total pengkondisian manual kuota kategori.
        # Keputusan murni diserahkan pada urutan nilai akhir SAW terbesar secara objektif.
        top_indices = final_scores.argsort()[::-1]
        raw_results = []
        
        for idx in top_indices:
            score_v = float(final_scores[idx])
            if score_v <= 0: 
                continue
            raw_results.append((idx, score_v))
            if len(raw_results) >= top_n: 
                break

        # =====================================================================
        # 7. GENERASI OUTPUT REKOMENDASI & REASONING META
        # =====================================================================
        results = []
        for rank, (idx, score_v) in enumerate(raw_results, start=1):
            row = self.df.iloc[idx]
            
            p_min = row.get("harga_min_clean", 0)
            p_max = row.get("harga_max_clean", 0)
            target_price = p_max if p_max > 0 else p_min
            harga_display = f"Rp {int(target_price):,}".replace(",", ".") if target_price > 0 else "Harga tidak tersedia"
            
            match_kategori = list(set(keyword_manager.CANONICAL_MAP.get(cat.lower(), cat.title()) for cat in extracted_products if cat.lower() in str(row.get("kategori_clean", "")).lower())) if extracted_products else [str(row.get("kategori_clean", "")).title()]
            match_kandungan = list(set(keyword_manager.CANONICAL_MAP.get(ing.lower(), ing.title()) for ing in extracted_ingredients if ing.lower() in str(row.get("kandungan_clean", "")).lower())) if extracted_ingredients else []
            match_masalah = list(set(keyword_manager.CANONICAL_MAP.get(prob.lower(), prob.title()) for prob in extracted_problems if prob.lower() in str(row.get("content_metadata", "")).lower())) if extracted_problems else []
            match_tipe = list(set(keyword_manager.CANONICAL_MAP.get(st.lower(), st.title()) for st in extracted_skin_types if st.lower() in str(row.get("content_metadata", "")).lower())) if extracted_skin_types else []

            kategori_str = ", ".join(match_kategori) if match_kategori and match_kategori[0] else "Produk"
            kandungan_str = ", ".join(match_kandungan) if match_kandungan else ""
            target_str = ", ".join(match_tipe + match_masalah)

            if kandungan_str and target_str:
                reasoning_text = f"Sesuai pencarianmu, {kategori_str} ini memiliki formulasi {kandungan_str} yang terbukti efektif merawat {target_str}."
            elif kandungan_str:
                reasoning_text = f"Sesuai pencarianmu, {kategori_str} ini direkomendasikan karena mengandung {kandungan_str}."
            elif target_str:
                reasoning_text = f"Sesuai pencarianmu, {kategori_str} ini sangat cocok untuk merawat {target_str}."
            else:
                reasoning_text = f"Rekomendasi {kategori_str} terbaik dengan tingkat kemiripan klinis yang tinggi untuk kebutuhan kulitmu."

            active_precautions = []
            if extracted_ingredients:
                for ing in extracted_ingredients:
                    ing_lower = ing.lower().strip()
                    if ing_lower in keyword_manager.PRECAUTION_MAP:
                        active_precautions.append(keyword_manager.PRECAUTION_MAP[ing_lower])

            reasoning_meta = {
                "reason_code": "SAW_MURNI_6_CRITERIA", 
                "matched_categories": match_kategori,
                "matched_ingredients": match_kandungan,
                "reasoning_text": reasoning_text,
                "precaution_notes": list(set(active_precautions)),
                "scoring_details": {
                    "raw_cbf_cosine": round(float(c1_matrix[idx]), 4),
                    "raw_price": round(float(c6_matrix[idx]), 4)
                }
            }

            results.append({
                "rank": rank,
                "product_id": int(row.get("product_id", 0)),
                "product_name": row.get("nama_produk", ""),
                "brand": row.get("nama_brand", ""),
                "category": row.get("kategori_clean", ""),
                "description": row.get("deskripsi_clean", ""),
                "ingredients": row.get("kandungan_clean", ""),
                "harga_display": harga_display,
                "image_url": row.get("image", ""),
                "link_produk": row.get("link_produk", ""),
                "similarity_score": round(score_v, 4),
                "reasoning_meta": reasoning_meta
            })

        # =====================================================================
        # 8. LOGGER SUMMARY REPORT
        # =====================================================================
        print("\n" + "="*85)
        print("[RECOMMENDER] HASIL ANALISIS KEMIRIPAN & AGREGASI SAW MURNI")
        print("="*85)
        print(f"{'Rank':<5} | {'Cosine (C1)':<12} | {'Harga (C6)':<10} | {'Total Utility SAW':<20} | {'Nama Produk'}")
        print("-" * 85)
        for res in results:
            cosine_val = res['reasoning_meta']['scoring_details']['raw_cbf_cosine']
            price_val = res['reasoning_meta']['scoring_details']['raw_price']
            saw_val = res['similarity_score']
            name = res['product_name'][:35] + "..." if len(res['product_name']) > 35 else res['product_name']
            print(f"{res['rank']:<5} | {cosine_val:<12.4f} | {price_val:<10.4f} | {saw_val:<20.4f} | {name}")
        print("="*85 + "\n")

        return results