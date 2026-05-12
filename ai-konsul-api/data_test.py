import pandas as pd

# Load dataset asli kamu
df = pd.read_csv("data/Sociolla.csv")

# Cari semua produk yang namanya mengandung "SKIN1004" dan "Toner"
cek_produk = df[
    df['nama_produk'].str.contains("SKIN1004", case=False, na=False) &
    df['nama_produk'].str.contains("Toner", case=False, na=False)
]

# Tampilkan nama produk dan kategorinya
print("Hasil pencarian SKIN1004 Toner di database:")
print(cek_produk[['nama_produk', 'kategori_produk']])