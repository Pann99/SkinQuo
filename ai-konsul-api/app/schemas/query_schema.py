import re
from typing import Optional
from pydantic import BaseModel, Field, field_validator

class QueryRequest(BaseModel):
    # Batasi panjang string (min 5, max 500 karakter disesuaikan dengan frontend)
    query: str = Field(
        ..., 
        min_length=5, 
        max_length=500, 
        description="Teks query dari pengguna untuk mencari rekomendasi"
    )
    
    # [NEW] Menambahkan field budget pengguna (Hanya Maksimal agar UI simpel)
    harga_max: Optional[int] = Field(
        None, 
        description="Batas maksimum budget user"
    )

    @field_validator('query')
    @classmethod
    def validate_safe_input(cls, v: str) -> str:
        # 1. Cek Karakter Berbahaya(XSS, SQL Injection, Command Injection)
        hacking_pattern = re.compile(
            r'(<script.*?>.*?</script>)|(<.*?>)|'
            r'(\b(SELECT|UNION|INSERT|UPDATE|DELETE|DROP|EXEC|ALTER|CREATE|TRUNCATE)\b)|'
            r'(--|\bOR\b\s+\d+=\d+|;)', 
            re.IGNORECASE
        )
        if hacking_pattern.search(v):
            raise ValueError("⚠️ Peringatan Keamanan: Terdeteksi query berbahaya (Indikasi Hacking/Injection). Akses ditolak.")
        
        # 2. Cek Spam Karakter Berulang (contoh: "aaaaaa", "wkwkwkwk")
        if re.search(r'(.)\1{4,}', v):
            raise ValueError("⚠️ Peringatan Spam: Input tidak wajar (terlalu banyak karakter berulang). Harap jelaskan kondisi kulit Anda dengan benar.")

        # 3. Cek Spam Ejaan / Gibberish (Konsonan berderet tanpa vokal)
        if re.search(r'[bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ]{6,}', v):
            raise ValueError("⚠️ Peringatan Spam: Ejaan tidak masuk akal terdeteksi. Harap gunakan bahasa yang dapat dipahami.")

        # 4. Pastikan string tidak cuma berisi angka atau simbol acak
        if len(re.findall(r'[a-zA-Z]', v)) < 3:
            raise ValueError("Teks harus mengandung setidaknya beberapa kata yang jelas untuk dianalisis.")
            
        return v