# Image Assets untuk SkinQuo

Direktori ini berisi semua image assets yang diperlukan oleh aplikasi SkinQuo.

## File yang Diperlukan

### 1. **hero-model.png**
- **Lokasi**: `public/images/hero-model.png`
- **Ukuran Ideal**: 600px × 750px
- **Format**: PNG dengan transparency
- **Deskripsi**: Foto model dengan kulit glowing untuk hero section halaman utama
- **Kegunaan**: Ditampilkan di sebelah kanan hero section di halaman home

### 2. **auth-model.png**
- **Lokasi**: `public/images/auth-model.png`
- **Ukuran Ideal**: 600px × 800px
- **Format**: PNG atau JPG
- **Deskripsi**: Foto model untuk halaman login dan register (di panel kanan)
- **Kegunaan**: Ditampilkan di sebelah kanan form login/register

## Catatan Penting

- Semua image harus dioptimalkan untuk web (compressed)
- Gunakan format PNG untuk transparency, JPG untuk foto regular
- Pastikan ukuran file tidak terlalu besar (< 500KB)
- Untuk production, gunakan image CDN jika memungkinkan

## Struktur Folder

```
public/images/
├── hero-model.png
├── auth-model.png
└── README.md (file ini)
```

## How to Add Images

1. Letakkan file image di folder `public/images/`
2. Pastikan nama file sesuai dengan yang direferensikan di view Blade
3. Clear Laravel cache jika diperlukan

## Contoh Penggunaan di Blade View

```blade
<!-- Hero Model -->
<img src="{{ asset('images/hero-model.png') }}" alt="Model dengan kulit glowing">

<!-- Auth Model -->
<img src="{{ asset('images/auth-model.png') }}" alt="SkinQuo Model">
```

---
Generated: April 10, 2026
