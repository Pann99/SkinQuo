import pandas as pd
import time
import httpx

from app.core.supabase_client import supabase

from app.utils.Database_processing import (
    extract_description,
    extract_ingredients,
    extract_category,
    extract_usage,
    extract_price,
    create_content_feature,   
    print_summary,
)

def run_pipeline_supabase() -> pd.DataFrame:
    print("[API] Menarik data produk dari Supabase...")

    # =========================================================================
    # [NEW] SISTEM AUTO-RETRY UNTUK MENCEGAH "SERVER DISCONNECTED"
    # =========================================================================
    max_retries = 3
    response = None
    
    for attempt in range(max_retries):
        try:
            if attempt > 0:
                print(f"[API] Mencoba ulang menarik data... (Percobaan {attempt + 1}/{max_retries})")
            
            response = (
                supabase
                .table("products")
                .select("*")
                .execute()
            )
            break  # Jika berhasil, langsung keluar dari loop retry
            
        except httpx.RemoteProtocolError as e:
            print(f"❌ [DATABASE ERROR] Koneksi terputus (RemoteProtocolError): {str(e)}")
            if attempt < max_retries - 1:
                print("⏳ Menunggu 3 detik sebelum mencoba lagi...")
                time.sleep(3)
            else:
                print("🚨 [CRITICAL ERROR] Gagal terhubung ke Supabase setelah 3 percobaan.")
                raise e
        except Exception as e:
            print(f"❌ [DATABASE ERROR] Terjadi kesalahan jaringan tak terduga: {str(e)}")
            if attempt < max_retries - 1:
                print("⏳ Menunggu 3 detik sebelum mencoba lagi...")
                time.sleep(3)
            else:
                raise e
    # =========================================================================

    if not response or not response.data:
        print("Error: Tidak ada data ditemukan di Supabase.")
        return pd.DataFrame()

    df = pd.DataFrame(response.data)
    df.columns = (
        df.columns
        .str.strip()
        .str.lower()
    )
    print("\n========== RAW DF ==========")
    print(df.head(2))

    df = extract_description(df)
    df = extract_ingredients(df)
    df = extract_category(df)
    df = extract_usage(df)
    
    # [NEW] Pipeline Harga
    df = extract_price(df)

    df = create_content_feature(df)

    # Validasi Final
    required_columns = [
        "product_id",
        "nama_produk",
        "nama_brand",
        "deskripsi_clean",
        "kandungan_clean",
        "kategori_clean",
        "cara_pakai_clean",  
        "harga_min_clean",    # <-- Wajib
        "harga_max_clean",    # <-- Wajib
        "content_metadata",   
    ]

    missing_columns = [
        col
        for col in required_columns
        if col not in df.columns
    ]

    if missing_columns:
        raise ValueError(
            f"Missing columns after pipeline: {missing_columns}"
        )

    print_summary(df)
    return df