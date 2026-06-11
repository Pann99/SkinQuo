import re
import pandas as pd
from app.utils.text_preprocessing import preprocess_text

def load_data(filepath: str) -> pd.DataFrame:
    df = pd.read_csv(filepath)
    print("=" * 60)
    print(f"Dataset berhasil dimuat: {filepath}")
    print(f"  Jumlah baris    : {len(df)}")
    print(f"  Jumlah kolom    : {len(df.columns)}")
    print("=" * 60)
    return df

def clean_description(text: str) -> str:
    if not isinstance(text, str):
        return ""
    text = re.sub(r"[\r\n\t]+", " ", text)
    text = re.sub(r" {2,}", " ", text)
    return text.strip()

def extract_description(df: pd.DataFrame) -> pd.DataFrame:
    print("\n[DESKRIPSI] Memulai ekstraksi & cleaning...")
    df["deskripsi_clean"] = df["deskripsi"].apply(
        lambda x: preprocess_text(str(x)) if pd.notna(x) else ""
    )
    print("[DESKRIPSI] Selesai.\n")
    return df

def clean_ingredient_text(text: str) -> str:
    if not isinstance(text, str):
        return ""
    text = re.sub(r"[\r\n\t]+", " ", text)
    text = re.sub(r" {2,}", " ", text)
    return text.strip()

def parse_ingredients(text: str) -> list[str]:
    if not text:
        return []
    return [item.strip() for item in text.split(",") if item.strip()]

def extract_ingredients(df: pd.DataFrame) -> pd.DataFrame:
    print("[KANDUNGAN] Memulai ekstraksi & cleaning...")
    df["ingredients_list"] = df["kandungan"].apply(
        lambda x: parse_ingredients(str(x)) if pd.notna(x) else []
    )
    df["kandungan_clean"] = df["kandungan"].apply(
        lambda x: preprocess_text(str(x)) if pd.notna(x) else ""
    )
    print("[KANDUNGAN] Selesai.\n")
    return df

def clean_category(text: str) -> str:
    if not isinstance(text, str) or text.strip() == "":
        return "Uncategorized"
    text = re.sub(r"[\r\n\t]+", " ", text)
    text = re.sub(r" {2,}", " ", text)
    return text.strip().title()

def extract_category(df: pd.DataFrame) -> pd.DataFrame:
    print("[KATEGORI] Memulai cleaning...")
    df["kategori_clean"] = df["kategori_produk"].apply(clean_category)
    print("[KATEGORI] Selesai.\n")
    return df

def extract_usage(df: pd.DataFrame) -> pd.DataFrame:
    print("\n[CARA PAKAI] Memulai ekstraksi & cleaning...")
    df["cara_pakai_clean"] = df["cara_pakai"].apply(
        lambda x: preprocess_text(str(x)) if pd.notna(x) else ""
    )
    print("[CARA PAKAI] Selesai.\n")
    return df

def clean_price(val) -> float:
    try:
        if pd.isna(val) or str(val).strip() == "":
            return 0.0
        val_str = str(val).lower()
        val_str = re.sub(r'[^0-9]', '', val_str) # Hapus 'Rp', '.', ','
        return float(val_str) if val_str else 0.0
    except Exception:
        return 0.0

def extract_price(df: pd.DataFrame) -> pd.DataFrame:
    print("\n[HARGA] Memulai cleaning kolom harga...")
    # Cek apakah ada 2 kolom dari DB, atau cuma 1 kolom
    if "harga_min" in df.columns and "harga_max" in df.columns:
        df["harga_min_clean"] = df["harga_min"].apply(clean_price)
        df["harga_max_clean"] = df["harga_max"].apply(clean_price)
    elif "harga" in df.columns:
        df["harga_clean"] = df["harga"].apply(clean_price)
        df["harga_min_clean"] = df["harga_clean"]
        df["harga_max_clean"] = df["harga_clean"]
    else:
        df["harga_min_clean"] = 0.0
        df["harga_max_clean"] = 0.0
        
    print("[HARGA] Selesai.\n")
    return df

def create_content_feature(df: pd.DataFrame) -> pd.DataFrame:
    print("[CONTENT METADATA] Memulai penggabungan atribut...")
    def combine_features(row: pd.Series) -> str:
        parts = [
            str(row.get("kategori_clean", "")).strip(),
            str(row.get("nama_produk",    "")).strip(),
            str(row.get("deskripsi_clean","")).strip(),
            str(row.get("kandungan_clean","")).strip(),
            str(row.get("cara_pakai_clean","")).strip(),
        ]
        return " ".join(part for part in parts if part).strip()

    df["content_metadata"] = df.apply(combine_features, axis=1)
    print("[CONTENT METADATA] Selesai.\n")
    return df

def print_summary(df: pd.DataFrame) -> None:
    print("=" * 60)
    print("RINGKASAN DATASET BERSIH")
    print("=" * 60)
    print(f"  Total produk              : {len(df)}")
    print(f"  Produk punya harga        : {(df['harga_min_clean'] > 0).sum()}")
    print("=" * 60)

def export_data(df: pd.DataFrame, output_path: str) -> None:
    df_export = df.copy()
    if "ingredients_list" in df_export.columns:
        df_export["ingredients_list"] = df_export["ingredients_list"].apply(
            lambda lst: " | ".join(lst) if isinstance(lst, list) else ""
        )
    df_export.to_csv(output_path, index=False, encoding="utf-8-sig")
    print(f"\nDataset bersih disimpan ke: {output_path}")