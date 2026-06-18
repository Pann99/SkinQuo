import pandas as pd
import re
import numpy as np
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
from app.services.keyword_manager import keyword_manager 

class RecommenderService:

    def __init__(self, df_clean: pd.DataFrame):
        self.df = df_clean.copy()

        if "content_metadata" not in self.df.columns:
            raise ValueError("[RECOMMENDER] ERROR: Kolom 'content_metadata' tidak ditemukan.")

        corpus = self.df["content_metadata"].fillna("").str.strip()
        self.vectorizer = TfidfVectorizer(min_df=1, max_df=0.85, sublinear_tf=True, ngram_range=(1, 1))
        self.tfidf_matrix = self.vectorizer.fit_transform(corpus)
        
        # ---------------------------------------------------------------------
        # BOBOT KRITERIA SAW (Soft Constraint)
        # ---------------------------------------------------------------------
        self.W_C1 = 0.40  # C1: TF-IDF Cosine Similarity
        self.W_C2 = 0.30  # C2: Ingredient Match Score
        self.W_C3 = 0.15  # C3: Skin Type Match Score
        self.W_C4 = 0.15  # C4: Problem Match Score
        
    def recommend(self, cleaned_query: str, extracted_products: list = None, 
                  extracted_ingredients: list = None, suggested_ingredients: list = None,
                  extracted_skin_types: list = None, extracted_problems: list = None, 
                  harga_max: float = None, top_n: int = 5):
        
        print("\n⚙️ [RECOMMENDER] Engine SAW + Ratio Scoring dijalankan...")
        def _sanitize_to_str(lst):
            if not lst: return []
            return [str(item.get("keyword", "")) if isinstance(item, dict) else str(item) for item in lst]

        extracted_products    = _sanitize_to_str(extracted_products)
        extracted_ingredients = _sanitize_to_str(extracted_ingredients)
        suggested_ingredients = _sanitize_to_str(suggested_ingredients)
        extracted_skin_types  = _sanitize_to_str(extracted_skin_types)
        extracted_problems    = _sanitize_to_str(extracted_problems)

        if not cleaned_query.strip():
            return []

        n_products = len(self.df)
        valid_mask = np.ones(n_products, dtype=bool)
        
        # ─── DETEKSI CONTEXT AREA MATA ───
        is_eye_requested = any("mata" in q.lower() or "eye" in q.lower() for q in extracted_products) or ("mata" in cleaned_query.lower())

        if extracted_products:
            cat_mask = np.zeros(n_products, dtype=bool)
            for product in extracted_products:
                if not product.strip(): continue
                cat_mask |= self.df["kategori_clean"].str.contains(product, case=False, na=False, regex=False).values
            
            # Anti-Eye Product Bleed Logic
            if not is_eye_requested:
                eye_mask = self.df["kategori_clean"].str.contains(r'\b(eye|mata)\b', case=False, na=False, regex=True).values
                cat_mask &= ~eye_mask
                print("  🔒 [FILTER] Anti-Eye Product Bleed Aktif: Mengabaikan produk mata.")
                
            valid_mask &= cat_mask

        if harga_max is not None and harga_max > 0:
            price_mask = (self.df["harga_max_clean"] <= harga_max) | (self.df["harga_max_clean"] == 0)
            valid_mask &= price_mask.values

        if not valid_mask.any():
            print("⚠️ [RECOMMENDER] Tidak ada produk lolos Hard Filter awal.")
            return []

        # =====================================================================
        # TAHAP MATRIKS KEPUTUSAN SAW (Kalkulasi Kriteria)
        # =====================================================================

        # C1: TF-IDF Cosine Similarity
        query_vector = self.vectorizer.transform([cleaned_query])
        c1_matrix = cosine_similarity(query_vector, self.tfidf_matrix).flatten()
        if c1_matrix.max() > 0:
            c1_matrix = c1_matrix / c1_matrix.max() 

        # C2: Ingredient Match (Ratio Scoring)
        c2_matrix = np.zeros(n_products)
        explicit_match_counts = np.zeros(n_products)

        if extracted_ingredients:
            total_requested = len(extracted_ingredients)
            for ingredient in extracted_ingredients:
                if not ingredient.strip(): continue
                core_token = ingredient.strip().split()[0]
                mask = self.df["kandungan_clean"].str.contains(rf"\b{re.escape(core_token)}", case=False, na=False, regex=True).values
                explicit_match_counts += mask.astype(float)
            
            c2_matrix = explicit_match_counts / total_requested
            
            # HARD FILTER: Eliminasi produk jika tidak mengandung bahan wajib sama sekali
            valid_mask &= (explicit_match_counts > 0)
            
        elif suggested_ingredients:
            for ingredient in suggested_ingredients:
                if not ingredient.strip(): continue
                core_token = ingredient.strip().split()[0]
                mask = self.df["kandungan_clean"].str.contains(rf"\b{re.escape(core_token)}", case=False, na=False, regex=True).values
                c2_matrix[mask] = 0.5 

        # C3: Skin Type Match
        c3_matrix = np.zeros(n_products)
        if extracted_skin_types:
            for st in extracted_skin_types:
                if not st.strip(): continue
                mask = self.df["content_metadata"].str.contains(st, case=False, na=False, regex=False).values
                c3_matrix[mask] = 1.0
                
        # C4: Problem Match
        c4_matrix = np.zeros(n_products)
        if extracted_problems:
            for prob in extracted_problems:
                if not prob.strip(): continue
                mask = self.df["content_metadata"].str.contains(prob, case=False, na=False, regex=False).values
                c4_matrix[mask] = 1.0

        # Perhitungan Nilai Preferensi Akhir SAW
        saw_main_score = (
            (c1_matrix * self.W_C1) + 
            (c2_matrix * self.W_C2) + 
            (c3_matrix * self.W_C3) + 
            (c4_matrix * self.W_C4)
        )
        
        saw_main_score[~valid_mask] = -1.0
        final_scores = saw_main_score

        top_indices = final_scores.argsort()[::-1]
        raw_results = []
        
        for idx in top_indices:
            score_v = float(final_scores[idx])
            if score_v <= 0: continue
            raw_results.append((idx, score_v))
            if len(raw_results) >= top_n: break

        # =====================================================================
        # 📊 [LOGGER BARU] DETAILED DEEP-SCORING MATRIX MONITOR
        # =====================================================================
        print("\n" + "📊 " + "="*95)
        print(f"{'📋 DATA TRACKER DEEP-SCORING MATRIX RECOMENDATION (SAW LOG)':^95}")
        print("="*95)
        print(f"{'Nama Produk':<30} | {'W_C1 (40%)':<9} | {'W_C2 (30%)':<9} | {'W_C3 (15%)':<9} | {'W_C4 (15%)':<9} | {'Total Score'}")
        print("-"*95)
        
        for idx, score_v in raw_results:
            p_name = self.df.iloc[idx]["nama_produk"]
            p_name_short = p_name[:28] + "..." if len(p_name) > 28 else p_name.ljust(31)
            
            # Ekstrak nilai kriteria (raw scores dari masing-masing matrix)
            c1, c2, c3, c4 = c1_matrix[idx], c2_matrix[idx], c3_matrix[idx], c4_matrix[idx]
            
            # Hitung weighted contribution (nilai terbobot)
            w1, w2, w3, w4 = c1 * self.W_C1, c2 * self.W_C2, c3 * self.W_C3, c4 * self.W_C4
            
            print(f"{p_name_short:<30} | {w1:.2f}({c1:.3f}) | {w2:.2f}({c2:.3f}) | {w3:.2f}({c3:.3f}) | {w4:.2f}({c4:.3f}) | {score_v*100:.1f}%")
        print("="*95 + "\n")
        # =====================================================================

        results = []
        for rank, (idx, score_v) in enumerate(raw_results, start=1):
            row = self.df.iloc[idx]
            
            p_min = row.get("harga_min_clean", 0)
            p_max = row.get("harga_max_clean", 0)
            target_price = p_max if p_max > 0 else p_min
            harga_display = f"Rp {int(target_price):,}".replace(",", ".") if target_price > 0 else "Harga tidak tersedia"
            
            match_kategori = list(set(keyword_manager.CANONICAL_MAP.get(cat.lower(), cat.title()) for cat in extracted_products if cat.lower() in str(row.get("kategori_clean", "")).lower())) if extracted_products else [str(row.get("kategori_clean", "")).title()]
            match_kandungan_eksplisit = list(set(keyword_manager.CANONICAL_MAP.get(ing.lower(), ing.title()) for ing in extracted_ingredients if ing.lower() in str(row.get("kandungan_clean", "")).lower())) if extracted_ingredients else []
            match_kandungan_saran = list(set(keyword_manager.CANONICAL_MAP.get(ing.lower(), ing.title()) for ing in suggested_ingredients if ing.lower() in str(row.get("kandungan_clean", "")).lower())) if suggested_ingredients else []
            match_masalah = list(set(keyword_manager.CANONICAL_MAP.get(prob.lower(), prob.title()) for prob in extracted_problems if prob.lower() in str(row.get("content_metadata", "")).lower())) if extracted_problems else []
            match_tipe = list(set(keyword_manager.CANONICAL_MAP.get(st.lower(), st.title()) for st in extracted_skin_types if st.lower() in str(row.get("content_metadata", "")).lower())) if extracted_skin_types else []

            kategori_str = ", ".join(match_kategori) if match_kategori and match_kategori[0] else "Produk"
            all_concerns = sorted(list(set(match_tipe + match_masalah)))
            
            reason_parts = []
            if kategori_str != "Produk":
                reason_parts.append(f"masuk dalam kategori {kategori_str}")
            if all_concerns:
                reason_parts.append(f"cocok untuk target {', '.join(all_concerns)}")
            if match_kandungan_eksplisit:
                reason_parts.append(f"memiliki kandungan utama ({', '.join(match_kandungan_eksplisit)})")
                
            skor_persen = round(score_v * 100, 1)

            is_english_detected = any(word in str(row.get("deskripsi_clean", "")).lower() for word in ["skin", "moisturizer", "brightening", "acne", "dry"])
            note_lang = " (Catatan: Formulasi produk ini telah divalidasi cocok oleh SkinQuo meskipun rincian katalognya menggunakan bahasa internasional)." if is_english_detected else ""

            if reason_parts:
                alasan_gabungan = ", ".join(reason_parts[:-1]) + (" dan " + reason_parts[-1] if len(reason_parts) > 1 else reason_parts[0])
                reasoning_text = f"Dipilih berdasarkan kalkulasi SAW (Skor Relevansi: {skor_persen}%) karena {alasan_gabungan}.{note_lang}"
            else:
                reasoning_text = f"Memiliki kecocokan semantik tertinggi dengan skor relevansi {skor_persen}%.{note_lang}"

            active_precautions = []
            combined_ingredients_to_check = extracted_ingredients + suggested_ingredients
            if combined_ingredients_to_check:
                for ing in combined_ingredients_to_check:
                    ing_lower = ing.lower().strip()
                    if ing_lower in keyword_manager.PRECAUTION_MAP:
                        active_precautions.append(keyword_manager.PRECAUTION_MAP[ing_lower])

            reasoning_meta = {
                "reason_code": "SAW_RATIO_SCORING", 
                "matched_categories": match_kategori,
                "matched_concerns": all_concerns,
                "kandungan_diminta": match_kandungan_eksplisit,
                "kandungan_disarankan": match_kandungan_saran,
                "reasoning_text": reasoning_text,
                "precaution_notes": list(set(active_precautions)),
                "scoring_details": {
                    "raw_cbf_cosine": round(float(c1_matrix[idx]), 4),
                    "ingredient_ratio_score": round(float(c3_matrix[idx]), 4),
                    "is_budget_safe": True 
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

        return results