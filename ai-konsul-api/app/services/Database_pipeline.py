import pandas as pd
from app.utils.Database_processing import (
    load_data,
    extract_description,
    extract_ingredients,
    extract_category,
    print_summary,
)
 
 
# ─────────────────────────────────────────────
# MAIN PIPELINE
# ─────────────────────────────────────────────
 
def run_pipeline(input_path: str) -> pd.DataFrame:
    
    df = load_data(input_path)
    df = extract_description(df)
    df = extract_ingredients(df)
    df = extract_category(df)
    print_summary(df)
    return df
 
 
if __name__ == "__main__":
    INPUT_PATH = "data/Sociolla.csv"
 
    df_clean = run_pipeline(INPUT_PATH)
 
    # Preview 
    print("\nPreview 3 baris pertama (kolom utama):")
    preview_cols = [
        "nama_produk", "kategori_clean", "deskripsi_clean", "kandungan_clean"
    ]
    print(df_clean[preview_cols].head(3).to_string(max_colwidth=80))