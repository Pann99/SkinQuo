"""
Database_processing.py
=======================
Pipeline pemrosesan dataset produk Sociolla.
Mencakup:
  1. Load dataset
  2. Ekstraksi & cleaning kolom `deskripsi` (Product Description)
  3. Ekstraksi & cleaning kolom `kandungan` (Ingredients)
  4. Cleaning kolom `kategori_produk` (Product Category)
  5. Penggabungan atribut menjadi `content_metadata` (Bag of Words)
  6. Export hasil ke CSV bersih
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
    text = re.sub(r"[\r\n\t]+", " ", text)
    text = re.sub(r" {2,}", " ", text)
    text = text.strip()
    return text

def extract_description(df: pd.DataFrame) -> pd.DataFrame:
    print("\n[DESKRIPSI] Memulai ekstraksi & cleaning...")
    df["deskripsi_clean"] = df["deskripsi"].apply(
        lambda x: preprocess_text(str(x)) if pd.notna(x) else ""
    )

    total  = len(df)
    terisi = df["deskripsi_clean"].replace("", pd.NA).notna().sum()
    kosong = total - terisi

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
    df["ingredients_list"] = df["kandungan"].apply(
        lambda x: parse_ingredients(str(x)) if pd.notna(x) else []
    )
    df["kandungan_clean"] = df["kandungan"].apply(
        lambda x: preprocess_text(str(x)) if pd.notna(x) else ""
    )

    total     = len(df)
    terisi    = df["kandungan_clean"].replace("", pd.NA).notna().sum()
    kosong    = total - terisi
    avg_bahan = df["ingredients_list"].apply(len).replace(0, pd.NA).mean()

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
    total         = len(df)
    uncategorized = (df["kategori_clean"] == "Uncategorized").sum()
    kategori_unik = df["kategori_clean"].nunique()

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
# 5. CONTENT METADATA — Bag of Words (TF-IDF)
# ─────────────────────────────────────────────

def create_content_feature(df: pd.DataFrame) -> pd.DataFrame:
    """
    Menggabungkan kolom kategori_clean, nama_produk, deskripsi_clean,
    dan kandungan_clean menjadi satu representasi dokumen tunggal
    (Single Bag of Words) per produk.

    Kolom output: `content_metadata`

    Tujuan: Representasi vektor tunggal ini digunakan sebagai input
    TF-IDF untuk perhitungan Cosine Similarity pada sistem rekomendasi
    Content-Based Filtering.
    """
    print("[CONTENT METADATA] Memulai penggabungan atribut...")

    def combine_features(row: pd.Series) -> str:
        parts = [
            str(row.get("kategori_clean", "")).strip(),
            str(row.get("nama_produk",    "")).strip(),
            str(row.get("deskripsi_clean","")).strip(),
            str(row.get("kandungan_clean","")).strip(),
            str(row.get("cara_pakai_clean","")).strip(),
            
        ]
        # Filter bagian kosong agar tidak muncul spasi ganda
        return " ".join(part for part in parts if part).strip()

    df["content_metadata"] = df.apply(combine_features, axis=1)

    total  = len(df)
    terisi = df["content_metadata"].replace("", pd.NA).notna().sum()
    kosong = total - terisi

    print(f"  Total produk              : {total}")
    print(f"  content_metadata terisi   : {terisi}")
    print(f"  content_metadata kosong   : {kosong}")
    print("[CONTENT METADATA] Selesai.\n")

    return df
# ─────────────────────────────────────────────
# 6. SUMMARY & EXPORT
# ─────────────────────────────────────────────

def print_summary(df: pd.DataFrame) -> None:
    print("=" * 60)
    print("RINGKASAN DATASET BERSIH")
    print("=" * 60)
    print(f"  Total produk              : {len(df)}")
    print(f"  Total kolom               : {len(df.columns)}")
    print(f"  Produk punya deskripsi    : {df['deskripsi_clean'].replace('', pd.NA).notna().sum()}")
    print(f"  Produk punya kandungan    : {df['kandungan_clean'].replace('', pd.NA).notna().sum()}")
    print(f"  Produk punya cara pakai   : {df['cara_pakai_clean'].replace('', pd.NA).notna().sum()}") # <-- TAMBAH BARIS INI
    print(f"  Kategori unik             : {df['kategori_clean'].nunique()}")
    print(f"  content_metadata terisi   : {df['content_metadata'].replace('', pd.NA).notna().sum()}")
    print("=" * 60)

def export_data(df: pd.DataFrame, output_path: str) -> None:
    df_export = df.copy()
    df_export["ingredients_list"] = df_export["ingredients_list"].apply(
        lambda lst: " | ".join(lst) if lst else ""
    )
    df_export.to_csv(output_path, index=False, encoding="utf-8-sig")
    print(f"\nDataset bersih disimpan ke: {output_path}")

# Tambahkan fungsi ini di Database_processing.py

def extract_usage(df: pd.DataFrame) -> pd.DataFrame:
    print("\n[CARA PAKAI] Memulai ekstraksi & cleaning...")
    # Menggunakan fungsi preprocess_text bawaan yang sudah aman dari stopword & stemming
    df["cara_pakai_clean"] = df["cara_pakai"].apply(
        lambda x: preprocess_text(str(x)) if pd.notna(x) else ""
    )

    total  = len(df)
    terisi = df["cara_pakai_clean"].replace("", pd.NA).notna().sum()
    kosong = total - terisi

    print(f"  Total produk         : {total}")
    print(f"  Cara Pakai tersedia  : {terisi}")
    print(f"  Cara Pakai kosong    : {kosong}")
    print("[CARA PAKAI] Selesai.\n")

    return df
