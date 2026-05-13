import pandas as pd

from app.core.supabase_client import supabase

from app.utils.Database_processing import (
    extract_description,
    extract_ingredients,
    extract_category,
    print_summary,
)


def run_pipeline_supabase() -> pd.DataFrame:

    print("[API] Menarik data produk dari Supabase...")

    # =====================================================
    # FETCH DATA
    # =====================================================

    response = (
        supabase
        .table("products")
        .select("*")
        .execute()
    )

    if not response.data:

        print("Error: Tidak ada data ditemukan di Supabase.")

        return pd.DataFrame()

    # =====================================================
    # RAW DATAFRAME
    # =====================================================

    df = pd.DataFrame(response.data)

    # NORMALISASI NAMA KOLOM
    df.columns = (
        df.columns
        .str.strip()
        .str.lower()
    )

    print("\n========== RAW DF ==========")
    print(df.head(2))
    print("\nCOLUMNS:")
    print(df.columns.tolist())
    print("============================")

    # =====================================================
    # DESCRIPTION CLEANING
    # =====================================================

    df = extract_description(df)

    print("\n========== AFTER DESCRIPTION ==========")
    print(df.columns.tolist())
    print("=======================================")

    # =====================================================
    # INGREDIENT CLEANING
    # =====================================================

    df = extract_ingredients(df)

    print("\n========== AFTER INGREDIENT ==========")
    print(df.columns.tolist())
    print("======================================")

    # =====================================================
    # CATEGORY CLEANING
    # =====================================================

    df = extract_category(df)

    print("\n========== AFTER CATEGORY ==========")
    print(df.columns.tolist())
    print("====================================")

    # =====================================================
    # VALIDASI FINAL
    # =====================================================

    required_columns = [
        "nama_produk",
        "nama_brand",
        "deskripsi_clean",
        "kandungan_clean",
        "kategori_clean"
    ]

    missing_columns = [
        col
        for col in required_columns
        if col not in df.columns
    ]

    if missing_columns:

        raise ValueError(
            f"Missing columns after pipeline: "
            f"{missing_columns}"
        )

    print_summary(df)

    print("\n========== FINAL DF ==========")
    print(df.head(2))
    print(df.columns.tolist())
    print("==============================")

    return df