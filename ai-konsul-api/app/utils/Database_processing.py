"""
Database_processing.py
=======================
Pipeline pemrosesan dataset produk Sociolla.
Mencakup:
  1. Load dataset
  2. Ekstraksi & cleaning kolom `deskripsi` (Product Description)
  3. Ekstraksi & cleaning kolom `kandungan` (Ingredients)
  4. Cleaning kolom `kategori_produk` (Product Category)
  5. Export hasil ke CSV bersih
"""

import re
import pandas as pd
from app.utils.text_preprocessing import preprocess_text

# ─────────────────────────────────────────────
# 1. LOAD DATASET
# ─────────────────────────────────────────────

def load_data(filepath: str) -> pd.DataFrame:
    df = pd.read_csv(filepath)
    print("=" * 60)
    print(f"Dataset berhasil dimuat: {filepath}")
    print(f"  Jumlah baris    : {len(df)}")
    print(f"  Jumlah kolom    : {len(df.columns)}")
    print(f"  Kolom           : {df.columns.tolist()}")
    print("=" * 60)
    return df


# ─────────────────────────────────────────────
# 2. PRODUCT DESCRIPTION — Ekstraksi & Cleaning
# ─────────────────────────────────────────────

def clean_description(text: str) -> str:
    if not isinstance(text, str):
        return ""
    # Hapus newline dan tab, ganti dengan spasi
    text = re.sub(r"[\r\n\t]+", " ", text)
    # Hapus spasi berulang
    text = re.sub(r" {2,}", " ", text)
    # Strip
    text = text.strip()
    return text


def extract_description(df: pd.DataFrame) -> pd.DataFrame:
    print("\n[DESKRIPSI] Memulai ekstraksi & cleaning...")
    # Terapkan preprocess_text agar di-stemming dan stopword dihilangkan
    df["deskripsi_clean"] = df["deskripsi"].apply(lambda x: preprocess_text(str(x)) if pd.notna(x) else "")

    total       = len(df)
    terisi      = df["deskripsi_clean"].replace("", pd.NA).notna().sum()
    kosong      = total - terisi

    print(f"  Total produk         : {total}")
    print(f"  Deskripsi tersedia   : {terisi}")
    print(f"  Deskripsi kosong     : {kosong}")
    print("[DESKRIPSI] Selesai.\n")

    return df


# ─────────────────────────────────────────────
# 3. INGREDIENTS — Ekstraksi & Cleaning
# ─────────────────────────────────────────────

def clean_ingredient_text(text: str) -> str:
  
    if not isinstance(text, str):
        return ""
    text = re.sub(r"[\r\n\t]+", " ", text)
    text = re.sub(r" {2,}", " ", text)
    return text.strip()


def parse_ingredients(text: str) -> list[str]:

    if not text:
        return []

    ingredients = [item.strip() for item in text.split(",") if item.strip()]
    return ingredients


def extract_ingredients(df: pd.DataFrame) -> pd.DataFrame:
    print("[KANDUNGAN] Memulai ekstraksi & cleaning...")
    # Ekstrak list bahan mentah dulu untuk JSON/Tampilan (opsional jika masih butuh list asli)
    df["ingredients_list"] = df["kandungan"].apply(lambda x: parse_ingredients(str(x)) if pd.notna(x) else [])
    # Terapkan preprocess_text untuk kebutuhan TF-IDF (Stemming dll)
    df["kandungan_clean"] = df["kandungan"].apply(lambda x: preprocess_text(str(x)) if pd.notna(x) else "")

    total      = len(df)
    terisi     = df["kandungan_clean"].replace("", pd.NA).notna().sum()
    kosong     = total - terisi
    avg_bahan  = df["ingredients_list"].apply(len).replace(0, pd.NA).mean()

    print(f"  Total produk            : {total}")
    print(f"  Kandungan tersedia      : {terisi}")
    print(f"  Kandungan kosong        : {kosong}")
    print(f"  Rata-rata bahan/produk  : {avg_bahan:.1f}")
    print("[KANDUNGAN] Selesai.\n")

    return df


# ─────────────────────────────────────────────
# 4. CATEGORY — Cleaning & Standardisasi
# ─────────────────────────────────────────────

def clean_category(text: str) -> str:

    if not isinstance(text, str) or text.strip() == "":
        return "Uncategorized"

    text = re.sub(r"[\r\n\t]+", " ", text)
    text = re.sub(r" {2,}", " ", text)
    text = text.strip().title()
    return text


def extract_category(df: pd.DataFrame) -> pd.DataFrame:

    print("[KATEGORI] Memulai cleaning...")

    df["kategori_clean"] = df["kategori_produk"].apply(clean_category)

    total          = len(df)
    uncategorized  = (df["kategori_clean"] == "Uncategorized").sum()
    kategori_unik  = df["kategori_clean"].nunique()

    print(f"  Total produk          : {total}")
    print(f"  Kategori unik         : {kategori_unik}")
    print(f"  Produk tanpa kategori : {uncategorized}")
    print(f"\n  Distribusi kategori:")
    dist = df["kategori_clean"].value_counts()
    for kategori, jumlah in dist.items():
        print(f"    {kategori:<35} : {jumlah} produk")
    print("[KATEGORI] Selesai.\n")

    return df


# ─────────────────────────────────────────────
# 5. SUMMARY & EXPORT
# ─────────────────────────────────────────────

def print_summary(df: pd.DataFrame) -> None:
   
    print("=" * 60)
    print("RINGKASAN DATASET BERSIH")
    print("=" * 60)
    print(f"  Total produk          : {len(df)}")
    print(f"  Total kolom           : {len(df.columns)}")
    print(f"  Produk punya deskripsi: {df['deskripsi_clean'].replace('', pd.NA).notna().sum()}")
    print(f"  Produk punya kandungan: {df['kandungan_clean'].replace('', pd.NA).notna().sum()}")
    print(f"  Kategori unik         : {df['kategori_clean'].nunique()}")
    print("=" * 60)


def export_data(df: pd.DataFrame, output_path: str) -> None:
   
    # Simpan ingredients_list sebagai string (JSON-like) agar CSV-compatible
    df_export = df.copy()
    df_export["ingredients_list"] = df_export["ingredients_list"].apply(
        lambda lst: " | ".join(lst) if lst else ""
    )
    df_export.to_csv(output_path, index=False, encoding="utf-8-sig")
    print(f"\nDataset bersih disimpan ke: {output_path}")


# ─────────────────────────────────────────────
# MAIN PIPELINE
# ─────────────────────────────────────────────

def run_pipeline(input_path: str, output_path: str) -> pd.DataFrame:
  
    df = load_data(input_path)
    df = extract_description(df)
    df = extract_ingredients(df)
    df = extract_category(df)
    print_summary(df)
    export_data(df, output_path)
    return df


if __name__ == "__main__":
    INPUT_PATH  = "Sociolla.csv"
    OUTPUT_PATH = "Sociolla_clean.csv"

    df_clean = run_pipeline(INPUT_PATH, OUTPUT_PATH)

    # Preview hasil
    print("\nPreview 3 baris pertama (kolom utama):")
    preview_cols = [
        "nama_produk", "kategori_clean", "deskripsi_clean", "kandungan_clean"
    ]
    print(df_clean[preview_cols].head(3).to_string(max_colwidth=80))