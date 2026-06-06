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

        # Inisialisasi TF-IDF Vectorizer (Core dari Content-Based Filtering)
        corpus = self.df["content_metadata"].fillna("").str.strip()
        self.vectorizer = TfidfVectorizer(
            min_df=1,
            max_df=0.85,
            sublinear_tf=True,
            ngram_range=(1, 1)
        )
        self.tfidf_matrix = self.vectorizer.fit_transform(corpus)
        
        # ─── KONFIGURASI BOBOT SAW (W) ───
        self.W_C1 = 0.40  # C1: Cosine Similarity (Content-Based Filtering)
        self.W_C2 = 0.30  # C2: Kecocokan Kategori Produk
        self.W_C3 = 0.20  # C3: Kecocokan Kandungan Aktif
        self.W_C4 = 0.10  # C4: Kecocokan Target Keluhan
        
        print("[RECOMMENDER] READY WITH CBF + SAW ARCHITECTURE")

    def recommend(self, cleaned_query: str, extracted_products: list = None, extracted_constraints: list = None, extracted_concerns: list = None, top_n: int = 5):
        if not cleaned_query.strip():
            return []

        n_products = len(self.df)

        # =====================================================================
        # STAGE 1: PEMBENTUKAN MATRIKS KEPUTUSAN (X)
        # =====================================================================

        # C1 (Benefit): Nilai Cosine Similarity dari TF-IDF (Rentang 0 - 1)
        query_vector = self.vectorizer.transform([cleaned_query])
        c1_matrix = cosine_similarity(query_vector, self.tfidf_matrix).flatten()

        # C2 (Benefit): Boolean Match Kategori Produk (1.0 jika cocok, 0.0 jika tidak)
        c2_matrix = np.zeros(n_products)
        if extracted_products:
            for product in extracted_products:
                if not product.strip(): continue
                mask = self.df["kategori_clean"].str.contains(
                    product, case=False, na=False, regex=False
                ).values
                c2_matrix[mask] = 1.0

        # C3 (Benefit): Boolean Match Kandungan Aktif (1.0 jika cocok, 0.0 jika tidak)
        c3_matrix = np.zeros(n_products)
        if extracted_constraints:
            for ingredient in extracted_constraints:
                if not ingredient.strip(): continue
                core_token = ingredient.strip().split()[0]
                mask = self.df["kandungan_clean"].str.contains(
                    rf"\b{re.escape(core_token)}", case=False, na=False, regex=True
                ).values
                c3_matrix[mask] = 1.0

        # C4 (Benefit): Boolean Match Keluhan Kulit (1.0 jika cocok, 0.0 jika tidak)
        c4_matrix = np.zeros(n_products)
        if extracted_concerns:
            for concern in extracted_concerns:
                if not concern.strip(): continue
                mask = self.df["content_metadata"].str.contains(
                    concern, case=False, na=False, regex=False
                ).values
                c4_matrix[mask] = 1.0

        # =====================================================================
        # STAGE 2: NORMALISASI MATRIKS (R)
        # =====================================================================
        # Karena nilai maks absolut C1 s/d C4 adalah 1.0, Matriks X == Matriks R.
        R_c1 = c1_matrix
        R_c2 = c2_matrix
        R_c3 = c3_matrix
        R_c4 = c4_matrix

        # =====================================================================
        # STAGE 3: MENGHITUNG NILAI PREFERENSI (V) ATAU WEIGHTED SCORE
        # =====================================================================
        # Rumus SAW: V_i = Σ (w_j * r_ij)
        final_scores = (
            (R_c1 * self.W_C1) +
            (R_c2 * self.W_C2) +
            (R_c3 * self.W_C3) +
            (R_c4 * self.W_C4)
        )

        # =====================================================================
        # STAGE 4: FINAL RANKING & DISTRIBUSI KUOTA
        # =====================================================================
        top_indices = final_scores.argsort()[::-1]

        # ─── LOGGING SYSTEM UNTUK HUGGING FACE TERMINAL ───
        print("\n" + "="*80)
        print(f"[TRACKING SCORING] QUERY: '{cleaned_query}'")
        print(f"[EXTRACTED] Prod: {extracted_products} | Constraints: {extracted_constraints} | Concerns: {extracted_concerns}")
        print("-"*80)
        print(f"{'Nama Produk':<30} | {'Raw CBF':<8} | {'SAW V Score':<11} | Breakdown (Weighted C1-C4)")
        print("-"*80)
        
        log_count = 0
        for idx in top_indices:
            score_v = float(final_scores[idx])
            if score_v > 0 and log_count < 10:  # Batasi log hanya untuk 10 produk teratas berbobot
                nama_prod = self.df.iloc[idx].get("nama_produk", "Unknown")
                # Potong nama produk jika terlalu panjang untuk kerapian log terminal
                nama_prod_short = nama_prod[:28] + ".." if len(nama_prod) > 28 else nama_prod
                
                print(f"{nama_prod_short:<30} | {R_c1[idx]:.4f}  | {score_v:.4f}     | "
                      f"C1:{R_c1[idx]*self.W_C1:.3f} w, C2:{R_c2[idx]*self.W_C2:.3f} w, "
                      f"C3:{R_c3[idx]*self.W_C3:.3f} w, C4:{R_c4[idx]*self.W_C4:.3f} w")
                log_count += 1
        print("="*80 + "\n")
        # ──────────────────────────────────────────────────

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
            match_kandungan = [ing.title() for ing in extracted_constraints if ing.lower() in kandungan_produk or ing.lower() in deskripsi_produk] if extracted_constraints else []

            concerns_text = ", ".join([c.title() for c in extracted_concerns]) if extracted_concerns else "kebutuhan kulitmu"
            ingredients_text = ", ".join(match_kandungan) if match_kandungan else "bahan aktif formulasi khusus"
            kategori_text = match_kategori[0] if match_kategori else "Produk"
            
            if match_kandungan and extracted_concerns:
                reasoning_text = f"Sesuai pencarianmu, {kategori_text} ini memiliki formulasi khusus {ingredients_text} yang terbukti efektif untuk merawat kondisi {concerns_text}."
            elif extracted_concerns:
                reasoning_text = f"Berdasarkan analisis SAW, profil kandungan {kategori_text} ini memiliki nilai preferensi tinggi untuk mengatasi masalah {concerns_text}."
            elif match_kandungan:
                reasoning_text = f"Sesuai dengan kriteria preferensimu, {kategori_text} ini memuat {ingredients_text} untuk memaksimalkan hasil perawatanmu."
            else:
                reasoning_text = f"{kategori_text} ini memiliki tingkat kecocokan algoritmik tertinggi dengan deskripsi kondisi yang kamu berikan."

            active_precautions = []
            for ing in match_kandungan + (extracted_constraints or []):
                ing_lower = ing.lower()
                if ing_lower in keyword_manager.PRECAUTION_MAP:
                    active_precautions.append(keyword_manager.PRECAUTION_MAP[ing_lower])
            active_precautions = list(set(active_precautions))

            # Metadata Detail Keputusan SAW & CBF Raw Score
            reasoning_meta = {
                "reason_code": "SAW_OPTIMIZED",
                "matched_categories": match_kategori,
                "matched_ingredients": match_kandungan,
                "reasoning_text": reasoning_text,
                "precaution_notes": active_precautions,
                "scoring_details": {
                    "raw_cbf_cosine_similarity": round(float(R_c1[idx]), 4),
                    "raw_category_match": round(float(R_c2[idx]), 4),
                    "raw_ingredient_match": round(float(R_c3[idx]), 4),
                    "raw_concern_match": round(float(R_c4[idx]), 4),
                },
                "saw_breakdown_weighted": {
                    "c1_textual_similarity": round(float(R_c1[idx] * self.W_C1), 4),
                    "c2_category_match": round(float(R_c2[idx] * self.W_C2), 4),
                    "c3_ingredient_match": round(float(R_c3[idx] * self.W_C3), 4),
                    "c4_concern_match": round(float(R_c4[idx] * self.W_C4), 4)
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
                "similarity_score": round(score_v, 4), # Final SAW Score (V)
                "reasoning_meta": reasoning_meta
            })

        return results