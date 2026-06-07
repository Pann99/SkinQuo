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
                "[RECOMMENDER] ERROR: Kolom 'content_metadata' tidak ditemukan di dataframe."
            )

        corpus = self.df["content_metadata"].fillna("").str.strip()
        self.vectorizer = TfidfVectorizer(
            min_df=1, max_df=0.85, sublinear_tf=True, ngram_range=(1, 1)
        )
        self.tfidf_matrix = self.vectorizer.fit_transform(corpus)
        
        # ─── KONFIGURASI BOBOT SAW (W) TOTAL 1.0 (100%) ───
        self.W_C1 = 0.35  # C1: Cosine Similarity (TF-IDF)
        self.W_C2 = 0.15  # C2: Kecocokan Kategori Produk
        self.W_C3 = 0.20  # C3: Kecocokan Kandungan (Ingredient)
        self.W_C4 = 0.20  # C4: Kecocokan Jenis Kulit (Skin Type)
        self.W_C5 = 0.10  # C5: Kecocokan Keluhan (Problem)
        
        print("[RECOMMENDER] READY WITH CBF + SAW ARCHITECTURE (5 CRITERIA)")

    def recommend(self, cleaned_query: str, extracted_products: list = None, 
                  extracted_ingredients: list = None, extracted_skin_types: list = None, 
                  extracted_problems: list = None, top_n: int = 5):
        
        if not cleaned_query.strip():
            return []

        n_products = len(self.df)

        # =====================================================================
        # STAGE 1: PEMBENTUKAN MATRIKS KEPUTUSAN (X)
        # =====================================================================
        query_vector = self.vectorizer.transform([cleaned_query])
        c1_matrix = cosine_similarity(query_vector, self.tfidf_matrix).flatten()

        c2_matrix = np.zeros(n_products)
        if extracted_products:
            for product in extracted_products:
                if not product.strip(): continue
                mask = self.df["kategori_clean"].str.contains(
                    product, case=False, na=False, regex=False
                ).values
                c2_matrix[mask] = 1.0

        c3_matrix = np.zeros(n_products)
        if extracted_ingredients:
            for ingredient in extracted_ingredients:
                if not ingredient.strip(): continue
                core_token = ingredient.strip().split()[0]
                mask = self.df["kandungan_clean"].str.contains(
                    rf"\b{re.escape(core_token)}", case=False, na=False, regex=True
                ).values
                c3_matrix[mask] = 1.0

        c4_matrix = np.zeros(n_products)
        if extracted_skin_types:
            for st in extracted_skin_types:
                if not st.strip(): continue
                mask = self.df["content_metadata"].str.contains(
                    st, case=False, na=False, regex=False
                ).values
                c4_matrix[mask] = 1.0
                
        c5_matrix = np.zeros(n_products)
        if extracted_problems:
            for prob in extracted_problems:
                if not prob.strip(): continue
                mask = self.df["content_metadata"].str.contains(
                    prob, case=False, na=False, regex=False
                ).values
                c5_matrix[mask] = 1.0

        # =====================================================================
        # STAGE 2 & 3: NORMALISASI (R) & PERHITUNGAN PREFERENSI (V)
        # =====================================================================
        final_scores = (
            (c1_matrix * self.W_C1) +
            (c2_matrix * self.W_C2) +
            (c3_matrix * self.W_C3) +
            (c4_matrix * self.W_C4) +
            (c5_matrix * self.W_C5)
        )

        # =====================================================================
        # STAGE 4: FINAL RANKING & DISTRIBUSI KUOTA (UPGRADED LOGGING)
        # =====================================================================
        top_indices = final_scores.argsort()[::-1]

        # ─── LOG 1: EVALUASI KANDIDAT BERDASARKAN SKOR SAW (TOP 10) ───
        print("\n" + "="*100)
        print(f"[TRACKING SCORING] QUERY: '{cleaned_query}'")
        print(f"[EXTRACTED] Prod: {extracted_products} | Ingredient: {extracted_ingredients} | SkinType: {extracted_skin_types} | Problem: {extracted_problems}")
        print("-"*100)
        print(f"{'Nama Produk':<25} | {'Raw CBF':<7} | {'TOTAL V':<7} | Breakdown Weighted Score (C1 - C5)")
        print("-"*100)
        
        log_count = 0
        for idx in top_indices:
            score_v = float(final_scores[idx])
            if score_v > 0 and log_count < 10:
                nama_prod = self.df.iloc[idx].get("nama_produk", "Unknown")
                nama_prod_short = nama_prod[:23] + ".." if len(nama_prod) > 23 else nama_prod
                
                print(f"{nama_prod_short:<25} | {c1_matrix[idx]:.4f}  | {score_v:.4f}  | "
                      f"C1(CBF):{c1_matrix[idx]*self.W_C1:.3f} | C2(Prod):{c2_matrix[idx]*self.W_C2:.3f} | "
                      f"C3(Ingr):{c3_matrix[idx]*self.W_C3:.3f} | C4(Skin):{c4_matrix[idx]*self.W_C4:.3f} | "
                      f"C5(Prob):{c5_matrix[idx]*self.W_C5:.3f}")
                log_count += 1
        print("="*100)

        # ─── PROSES DISTRIBUSI KUOTA PRODUK ───
        raw_results = []
        max_quota = math.ceil(top_n / len(extracted_products)) if extracted_products else top_n
        category_counts = {prod.lower(): 0 for prod in extracted_products} if extracted_products else {}

        for idx in top_indices:
            score_v = float(final_scores[idx])
            if score_v <= 0: 
                continue

            row = self.df.iloc[idx]
            kategori_produk = str(row.get("kategori_clean", "")).strip().lower()

            if extracted_products:
                matched = False
                for prod in extracted_products:
                    if prod.lower() in kategori_produk:
                        if category_counts[prod.lower()] < max_quota:
                            category_counts[prod.lower()] += 1
                            raw_results.append((idx, score_v))
                        matched = True
                        break
                if matched:
                    if len(raw_results) >= top_n: break
                    continue
                continue 
            else:
                raw_results.append((idx, score_v))
                if len(raw_results) >= top_n: break

        # ─── LOG 2: HASIL AKHIR REKOMENDASI YANG DILOLOSKAN (TOP 1 S/D TOP 5) ───
        print("\n" + "★"*100)
        print(f" FINAL RANKING REKOMENDASI (TOP {len(raw_results)} DILOLOSKAN KE USER)")
        print("★"*100)
        print(f"{'Rank':<5} | {'Nama Brand':<15} | {'Nama Produk':<30} | {'Kategori':<15} | {'Final Score (V)':<15}")
        print("-"*100)
        for rank, (idx, score_v) in enumerate(raw_results, start=1):
            row = self.df.iloc[idx]
            brand = str(row.get("nama_brand", "Unknown"))[:13]
            nama_prod = str(row.get("nama_produk", "Unknown"))
            nama_prod_short = nama_prod[:28] + ".." if len(nama_prod) > 28 else nama_prod
            kategori = str(row.get("kategori_clean", "Unknown"))[:13]
            
            print(f"Rank {rank:<2} | {brand:<15} | {nama_prod_short:<30} | {kategori:<15} | {score_v:.4f}")
        print("★"*100 + "\n")

        # =====================================================================
        # STAGE 5: FORMATTING JSON RESPONSE
        # =====================================================================
        results = []
        for rank, (idx, score_v) in enumerate(raw_results, start=1):
            row = self.df.iloc[idx]
            
            kategori_produk = str(row.get("kategori_clean", "")).strip()
            kandungan_produk = str(row.get("kandungan_clean", "")).lower()
            deskripsi_produk = str(row.get("deskripsi_clean", "")).lower()

            match_kategori = [cat.title() for cat in extracted_products if cat.lower() in kategori_produk.lower()] if extracted_products else [kategori_produk]
            match_kandungan = [ing.title() for ing in extracted_ingredients if ing.lower() in kandungan_produk or ing.lower() in deskripsi_produk] if extracted_ingredients else []

            combined_concerns = (extracted_skin_types or []) + (extracted_problems or [])
            concerns_text = ", ".join([c.title() for c in combined_concerns]) if combined_concerns else "kebutuhan kulitmu"
            ingredients_text = ", ".join(match_kandungan) if match_kandungan else "bahan aktif khusus"
            kategori_text = match_kategori[0] if match_kategori else "Produk"
            
            if match_kandungan and combined_concerns:
                reasoning_text = f"Sesuai pencarianmu, {kategori_text} ini memiliki formulasi {ingredients_text} yang terbukti efektif untuk merawat kondisi {concerns_text}."
            elif combined_concerns:
                reasoning_text = f"Berdasarkan analisis SAW, profil {kategori_text} ini memiliki nilai kecocokan tinggi untuk masalah {concerns_text}."
            elif match_kandungan:
                reasoning_text = f"Sesuai preferensimu, {kategori_text} ini memuat {ingredients_text} untuk memaksimalkan hasil perawatan."
            else:
                reasoning_text = f"{kategori_text} ini memiliki tingkat kecocokan algoritmik tertinggi dengan deskripsi yang kamu berikan."

            active_precautions = []
            for ing in match_kandungan + (extracted_ingredients or []):
                ing_lower = ing.lower()
                if ing_lower in keyword_manager.PRECAUTION_MAP:
                    active_precautions.append(keyword_manager.PRECAUTION_MAP[ing_lower])
            active_precautions = list(set(active_precautions))

            reasoning_meta = {
                "reason_code": "SAW_OPTIMIZED_5_CRITERIA",
                "matched_categories": match_kategori,
                "matched_ingredients": match_kandungan,
                "reasoning_text": reasoning_text,
                "precaution_notes": active_precautions,
                "scoring_details": {
                    "raw_cbf_cosine": round(float(c1_matrix[idx]), 4),
                    "raw_category": round(float(c2_matrix[idx]), 4),
                    "raw_ingredient": round(float(c3_matrix[idx]), 4),
                    "raw_skin_type": round(float(c4_matrix[idx]), 4),
                    "raw_problem": round(float(c5_matrix[idx]), 4)
                },
                "saw_breakdown_weighted": {
                    "c1_textual": round(float(c1_matrix[idx] * self.W_C1), 4),
                    "c2_category": round(float(c2_matrix[idx] * self.W_C2), 4),
                    "c3_ingredient": round(float(c3_matrix[idx] * self.W_C3), 4),
                    "c4_skin_type": round(float(c4_matrix[idx] * self.W_C4), 4),
                    "c5_problem": round(float(c5_matrix[idx] * self.W_C5), 4)
                }
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
                "similarity_score": round(score_v, 4),
                "reasoning_meta": reasoning_meta
            })

        return results