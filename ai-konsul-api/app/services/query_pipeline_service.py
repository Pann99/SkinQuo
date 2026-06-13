from app.utils.text_preprocessing import preprocess_text
from app.services.keyword_fix_service import fix_query
from app.services.Quality_querycontrol import validate_query
from app.services.keyword_manager import keyword_manager

EXPANSION_RULES = {
    "Jerawat": ["salicylic acid", "centella asiatica", "tea tree", "niacinamide"],
    "Kulit Kusam": ["vitamin c", "niacinamide", "alpha arbutin", "licorice"],
    "Flek Hitam": ["retinol", "alpha arbutin", "niacinamide", "tranexamic acid"],
    "Komedo": ["salicylic acid", "bha", "aha"],
    "Kemerahan / Iritasi": ["centella asiatica", "ceramide", "panthenol", "allantoin"],
    "Kerutan / Penuaan": ["retinol", "peptide", "collagen", "bakuchiol"],
    "Kulit Kering": ["hyaluronic acid", "ceramide", "glycerin"],
    "Kulit Berminyak": ["salicylic acid", "niacinamide", "zinc"],
    "Kulit Sensitif": ["ceramide", "centella asiatica", "mugwort"]
}

def _extract_keywords_from_dicts(dict_list: list[dict]) -> list[str]:
    if not dict_list:
        return []
    return [item["keyword"] for item in dict_list if item["confidence"] >= 0.65]

# [DIPERBAIKI] Fungsi _remove_substrings yang membahayakan keyword mirip sudah dihapus.

class QueryPipelineService:

    def run(self, query: str):
        original_query = query
        cleaned_text   = preprocess_text(query)

        query_check = validate_query(cleaned_text, original_query)
        status      = query_check["status"]

        if status == "out_of_context":
            raise ValueError(
                "⚠️ Topik Tidak Dikenali: SkinQuo dirancang khusus untuk menganalisis "
                "kondisi kulit dan merekomendasikan skincare. Tolong ceritakan masalah kulitmu saja, ya!"
            )

        if status == "invalid":
            return {
                "cleaned_text":   cleaned_text,
                "status":         "invalid",
                "missing_points": query_check.get("missing", []),
                "matched_points": query_check.get("matched", {})
            }

        if status == "fixable":
            fixable_kws = query_check.get("fixable_keywords", {})
            correction = fix_query(original_query, cleaned_text, fixable_kws)
            
            if correction["is_fixable"]:
                fixed_raw_query = correction["fixed_query"]
                final_cleaned_text = preprocess_text(fixed_raw_query)
                query_check = validate_query(final_cleaned_text, fixed_raw_query)
                current_status = "fixable"
                fix_result = correction["fix_result"]
            else:
                final_cleaned_text = cleaned_text
                current_status = "valid"
                fix_result = None
        else:
            final_cleaned_text = cleaned_text
            current_status = "valid"
            fix_result = None

        matched = query_check.get("matched", {})
        
        # Ekstrak data murni menjadi List String
        raw_product    = _extract_keywords_from_dicts(matched.get("product", {}).get("exact", []))
        raw_ingredient = _extract_keywords_from_dicts(matched.get("ingredient", {}).get("exact", []))
        raw_skin_type  = _extract_keywords_from_dicts(matched.get("skin_type", {}).get("exact", []))
        raw_problem    = _extract_keywords_from_dicts(matched.get("problem", {}).get("exact", []))

        # [DIPERBAIKI] Deduplikasi Cerdas menggunakan Set langsung terhadap Canonical Map
        display_products    = sorted(list(set(keyword_manager.CANONICAL_MAP.get(p, p.title()) for p in raw_product)))
        display_ingredients = sorted(list(set(keyword_manager.CANONICAL_MAP.get(i, i.title()) for i in raw_ingredient)))
        display_skin_types  = sorted(list(set(keyword_manager.CANONICAL_MAP.get(s, s.title()) for s in raw_skin_type)))
        display_problems    = sorted(list(set(keyword_manager.CANONICAL_MAP.get(p, p.title()) for p in raw_problem)))

        # LAYER 4: QUERY EXPANSION
        expanded_ingredients = []
        combined_concerns = display_problems + display_skin_types
        
        for concern in combined_concerns:
            if concern in EXPANSION_RULES:
                expanded_ingredients.extend(EXPANSION_RULES[concern])
                
        # Injeksi ingredient ekspansi ke dalam raw data agar dikirim ke Recommender
        final_extracted_ingredients = list(set(raw_ingredient + expanded_ingredients))

        # --- LOGGER SUMMARY ---
        print("\n" + "="*60)
        print("🚀 [LAYER 4] QUERY EXPANSION & FINAL COMPRESSION")
        print("-" * 60)
        print(f"  Deteksi Keluhan (Concerns) : {combined_concerns}")
        if expanded_ingredients:
            print(f"  ✨ Auto-Inject Ingredients : {list(set(expanded_ingredients))}")
        else:
            print(f"  ✨ Auto-Inject Ingredients : Tidak ada pemicu")
        print(f"  Final Ingredients Output   : {final_extracted_ingredients}")
        print("="*60 + "\n")

        return {
            "cleaned_text":          final_cleaned_text,
            "status":                current_status,
            "matched_points":        matched,
            
            # Data Mentah (String) untuk Algoritma Recommender 
            "extracted_products":    raw_product,
            "extracted_ingredients": final_extracted_ingredients, 
            "extracted_skin_types":  raw_skin_type,
            "extracted_problems":    raw_problem,
            
            "display_explainability": {
                "Jenis Produk":       display_products,
                "Kandungan Aktif":    display_ingredients, 
                "Jenis/Tipe Kulit":   display_skin_types,
                "Keluhan Kulit":      display_problems,
                "Hidden Expansion":   list(set(expanded_ingredients)) 
            },
            "query_fixing":          fix_result,
        }