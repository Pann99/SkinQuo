# 📊 Perancangan Database SkinQuo

**Status:** Tahap Awal (Comprehensive Design)  
**Database:** PostgreSQL (Supabase)  
**Tanggal:** 13 April 2026

---

## 📋 Daftar Isi
1. [Entity Relationship Diagram (ERD)](#1-entity-relationship-diagram-erd)
2. [Kamus Data (Data Dictionary)](#2-kamus-data-data-dictionary)
3. [Relasi Database](#3-relasi-database)
4. [Normalisasi](#4-normalisasi)
5. [Constraints & Indexing](#5-constraints--indexing)
6. [Script SQL](#6-script-sql)

---

## 1. Entity Relationship Diagram (ERD)

### Entitas Utama dan Hubungannya:

```
┌─────────────────────────────────────────────────────────────────────┐
│                         DIAGRAM RELASI TABEL                         │
├─────────────────────────────────────────────────────────────────────┤

                            ┌──────────────┐
                            │    USERS     │
                            └──────────────┘
                                   │
                    ┌──────────────┼──────────────┐
                    │              │              │
              (1:1) │        (1:N) │        (1:N) │
                    │              │              │
        ┌───────────▼──────┐  ┌────▼─────────┐  ┌─────▼──────────────┐
        │ USER_PROFILES    │  │ ARTICLES     │  │ CONSULTATIONS      │
        └──────────────────┘  └──────────────┘  └────────────────────┘
                                     │                      │
                              (1:N)  │                      │
                                     │            ┌─────────▼──────────┐
                            ┌────────▼────────┐   │ CONSULTATION_RESULTS│
                            │ ARTICLE_TAGS    │   └────────────────────┘
                            └─────────────────┘            │
                                                    (1:N)  │
                            ┌──────────────┐  ┌───────────▼──────────┐
                            │  SKIN_TYPES  │  │ SKIN_CONDITION_ANALYSIS│
                            └──────────────┘  └──────────────────────┘
                                   │
                            (1:N)  │
                    ┌──────────────┼──────────────┐
                    │              │              │
            ┌───────▼────────┐  ┌──▼───────────┐ │
            │   PRODUCTS     │  │ PRODUCT_RECS │ │
            └────────────────┘  └───────────────┘ │
                   │                              │
            (M:N)  │                      ┌───────▼────────┐
                   │                      │ USER_FEEDBACKS │
            ┌──────▼────────┐            └────────────────┘
            │ PRODUCT_BRANDS│
            └───────────────┘

                  ┌──────────────┐
                  │ ADMIN_LOGS   │
                  └──────────────┘
```

### Entitas dan Deskripsinya:

| No | Entitas | Deskripsi |
|----|---------|-----------|
| 1 | **USERS** | Data pengguna (user & admin) dengan autentikasi |
| 2 | **USER_PROFILES** | Profil lengkap pengguna (relasi 1:1 dengan USERS) |
| 3 | **SKIN_TYPES** | Master data tipe kulit (Normal, Oily, Dry, Combination, Sensitive) |
| 4 | **ARTICLES** | Artikel edukasi skin care & skin guide |
| 5 | **ARTICLE_TAGS** | Tag untuk kategorisasi artikel (many-to-many relation) |
| 6 | **PRODUCTS** | Katalog produk skincare |
| 7 | **PRODUCT_BRANDS** | Brand/Merek produk (many-to-many relation) |
| 8 | **CONSULTATIONS** | Riwayat konsultasi kulit dari pengguna |
| 9 | **CONSULTATION_RESULTS** | Hasil analisis konsultasi dengan rekomendasi |
| 10 | **SKIN_CONDITION_ANALYSIS** | Detail analisis kondisi kulit dari konsultasi |
| 11 | **PRODUCT_RECS** | Rekomendasi produk berdasarkan hasil konsultasi |
| 12 | **USER_FEEDBACKS** | Feedback/review dari pengguna terhadap produk |
| 13 | **ADMIN_LOGS** | Log aktivitas admin (audit trail) |

---

## 2. Kamus Data (Data Dictionary)

### Tabel: `users`
**Fungsi:** Menyimpan data autentikasi user dan admin  
**Primary Key:** `id`

| Kolom | Tipe Data | Constraint | Deskripsi |
|-------|-----------|-----------|-----------|
| `id` | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | ID unik pengguna |
| `name` | VARCHAR(255) | NOT NULL | Nama lengkap pengguna |
| `email` | VARCHAR(255) | NOT NULL, UNIQUE | Email untuk login |
| `password` | VARCHAR(255) | NOT NULL | Password terenkripsi (bcrypt) |
| `role` | ENUM('user', 'admin') | NOT NULL, DEFAULT 'user' | Peran pengguna |
| `is_active` | BOOLEAN | NOT NULL, DEFAULT true | Status aktif pengguna |
| `last_login_at` | TIMESTAMP WITH TIME ZONE | NULL | Waktu login terakhir |
| `email_verified_at` | TIMESTAMP WITH TIME ZONE | NULL | Waktu verifikasi email |
| `created_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Waktu pembuatan akun |
| `updated_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Waktu update terakhir |
| `deleted_at` | TIMESTAMP WITH TIME ZONE | NULL | Soft delete timestamp |

---

### Tabel: `user_profiles`
**Fungsi:** Profil lengkap pengguna (extended info)  
**Primary Key:** `id`  
**Foreign Key:** `user_id` → `users(id)`

| Kolom | Tipe Data | Constraint | Deskripsi |
|-------|-----------|-----------|-----------|
| `id` | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | ID profil |
| `user_id` | UUID | NOT NULL, UNIQUE, FK | Referensi ke users |
| `phone` | VARCHAR(20) | NULL | Nomor telepon |
| `date_of_birth` | DATE | NULL | Tanggal lahir |
| `gender` | ENUM('male', 'female', 'other') | NULL | Jenis kelamin |
| `skin_type_id` | UUID | NULL, FK | Referensi ke skin_types |
| `avatar_url` | TEXT | NULL | URL foto profil |
| `bio` | TEXT | NULL | Biografi singkat |
| `address` | TEXT | NULL | Alamat |
| `city` | VARCHAR(100) | NULL | Kota |
| `province` | VARCHAR(100) | NULL | Provinsi |
| `postal_code` | VARCHAR(10) | NULL | Kode pos |
| `skin_concerns` | TEXT[] | NULL | Array concerns (acne, wrinkles, etc) |
| `created_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Dibuat tanggal |
| `updated_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Diupdate tanggal |

---

### Tabel: `skin_types`
**Fungsi:** Master data klasifikasi tipe kulit  
**Primary Key:** `id`

| Kolom | Tipe Data | Constraint | Deskripsi |
|-------|-----------|-----------|-----------|
| `id` | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | ID tipe kulit |
| `name` | VARCHAR(50) | NOT NULL, UNIQUE | Nama tipe (Normal, Oily, Dry, etc) |
| `slug` | VARCHAR(50) | NOT NULL, UNIQUE | URL-friendly identifier |
| `description` | TEXT | NULL | Deskripsi karakteristik |
| `characteristics` | TEXT[] | NULL | Array ciri-ciri tipe kulit |
| `icon_url` | TEXT | NULL | URL icon tipe kulit |
| `color_hex` | VARCHAR(7) | NULL | Warna untuk UI (#RRGGBB) |
| `is_active` | BOOLEAN | NOT NULL, DEFAULT true | Status aktif |
| `created_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Dibuat tanggal |
| `updated_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Diupdate tanggal |

**Data Contoh:**
- Normal, Oily, Dry, Combination, Sensitive

---

### Tabel: `articles`
**Fungsi:** Artikel edukasi dan skin guide  
**Primary Key:** `id`  
**Foreign Key:** `created_by` → `users(id)`

| Kolom | Tipe Data | Constraint | Deskripsi |
|-------|-----------|-----------|-----------|
| `id` | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | ID artikel |
| `title` | VARCHAR(255) | NOT NULL | Judul artikel |
| `slug` | VARCHAR(255) | NOT NULL, UNIQUE | URL slug |
| `excerpt` | VARCHAR(500) | NOT NULL | Ringkasan singkat |
| `content` | TEXT | NOT NULL | Konten lengkap (markdown) |
| `featured_image` | TEXT | NULL | URL gambar featured |
| `thumbnail` | TEXT | NULL | URL thumbnail |
| `category` | VARCHAR(100) | NOT NULL | Kategori (Moisturizing, Anti-Aging, Acne, etc) |
| `author` | VARCHAR(255) | NOT NULL | Nama author |
| `created_by` | UUID | NOT NULL, FK | User yang membuat artikel |
| `view_count` | INTEGER | NOT NULL, DEFAULT 0 | Jumlah views |
| `published_at` | TIMESTAMP WITH TIME ZONE | NULL | Waktu publikasi |
| `is_published` | BOOLEAN | NOT NULL, DEFAULT false | Status publikasi |
| `meta_description` | VARCHAR(160) | NULL | Meta description SEO |
| `meta_keywords` | VARCHAR(255) | NULL | Meta keywords SEO |
| `reading_time` | INTEGER | NULL | Estimasi waktu baca (menit) |
| `created_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Dibuat tanggal |
| `updated_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Diupdate tanggal |
| `deleted_at` | TIMESTAMP WITH TIME ZONE | NULL | Soft delete |

---

### Tabel: `article_tags`
**Fungsi:** Kategorisasi artikel (many-to-many)  
**Primary Key:** Composite (`article_id`, `tag_name`)

| Kolom | Tipe Data | Constraint | Deskripsi |
|-------|-----------|-----------|-----------|
| `id` | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | ID relasi |
| `article_id` | UUID | NOT NULL, FK | Referensi ke articles |
| `tag_name` | VARCHAR(100) | NOT NULL | Nama tag |
| `created_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Dibuat tanggal |

---

### Tabel: `products`
**Fungsi:** Katalog produk skincare  
**Primary Key:** `id`  
**Foreign Key:** `skin_type_id` → `skin_types(id)`

| Kolom | Tipe Data | Constraint | Deskripsi |
|-------|-----------|-----------|-----------|
| `id` | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | ID produk |
| `name` | VARCHAR(255) | NOT NULL | Nama produk |
| `slug` | VARCHAR(255) | NOT NULL, UNIQUE | URL slug |
| `sku` | VARCHAR(100) | NOT NULL, UNIQUE | Stock keeping unit |
| `description` | TEXT | NOT NULL | Deskripsi produk |
| `price` | DECIMAL(12, 2) | NOT NULL | Harga produk (IDR) |
| `discount_price` | DECIMAL(12, 2) | NULL | Harga diskon |
| `stock_quantity` | INTEGER | NOT NULL, DEFAULT 0 | Stok tersedia |
| `skin_type_id` | UUID | NOT NULL, FK | Tipe kulit yang cocok |
| `product_type` | VARCHAR(100) | NOT NULL | Tipe produk (Cleanser, Toner, Serum, etc) |
| `benefits` | TEXT[] | NULL | Array manfaat produk |
| `ingredients` | TEXT[] | NULL | Array bahan utama |
| `usage_instruction` | TEXT | NULL | Petunjuk penggunaan |
| `main_image` | TEXT | NULL | URL gambar utama |
| `gallery_images` | TEXT[] | NULL | Array URL galeri gambar |
| `rating` | DECIMAL(3, 2) | NOT NULL, DEFAULT 0 | Rating rata-rata (0-5) |
| `total_reviews` | INTEGER | NOT NULL, DEFAULT 0 | Total jumlah review |
| `is_active` | BOOLEAN | NOT NULL, DEFAULT true | Status aktif produk |
| `created_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Dibuat tanggal |
| `updated_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Diupdate tanggal |
| `deleted_at` | TIMESTAMP WITH TIME ZONE | NULL | Soft delete |

---

### Tabel: `product_brands`
**Fungsi:** Brand/Merek produk (many-to-many)  
**Primary Key:** Composite (`product_id`, `brand_id`)

| Kolom | Tipe Data | Constraint | Deskripsi |
|-------|-----------|-----------|-----------|
| `id` | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | ID relasi |
| `product_id` | UUID | NOT NULL, FK | Referensi ke products |
| `brand_id` | UUID | NOT NULL, FK | Referensi ke brands |
| `created_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Dibuat tanggal |

### Tabel: `brands`
**Fungsi:** Master data brand produk  
**Primary Key:** `id`

| Kolom | Tipe Data | Constraint | Deskripsi |
|-------|-----------|-----------|-----------|
| `id` | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | ID brand |
| `name` | VARCHAR(255) | NOT NULL, UNIQUE | Nama brand |
| `slug` | VARCHAR(255) | NOT NULL, UNIQUE | URL slug |
| `description` | TEXT | NULL | Deskripsi brand |
| `logo_url` | TEXT | NULL | URL logo |
| `website` | VARCHAR(255) | NULL | Website brand |
| `country` | VARCHAR(100) | NULL | Negara asal |
| `is_active` | BOOLEAN | NOT NULL, DEFAULT true | Status aktif |
| `created_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Dibuat tanggal |
| `updated_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Diupdate tanggal |

---

### Tabel: `consultations`
**Fungsi:** Riwayat konsultasi kulit dari pengguna  
**Primary Key:** `id`  
**Foreign Key:** `user_id` → `users(id)`

| Kolom | Tipe Data | Constraint | Deskripsi |
|-------|-----------|-----------|-----------|
| `id` | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | ID konsultasi |
| `user_id` | UUID | NOT NULL, FK | Referensi ke users |
| `consultation_date` | TIMESTAMP WITH TIME ZONE | NOT NULL | Tanggal konsultasi |
| `skin_type_detected` | UUID | NULL, FK | Tipe kulit terdeteksi |
| `status` | ENUM('pending', 'in_progress', 'completed', 'archived') | NOT NULL, DEFAULT 'pending' | Status konsultasi |
| `notes` | TEXT | NULL | Catatan konsultasi |
| `confidence_score` | DECIMAL(3, 2) | NULL | Skor kepercayaan diagnosis (0-1) |
| `created_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Dibuat tanggal |
| `updated_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Diupdate tanggal |

---

### Tabel: `consultation_results`
**Fungsi:** Hasil analisis konsultasi dengan rekomendasi  
**Primary Key:** `id`  
**Foreign Key:** `consultation_id` → `consultations(id)`

| Kolom | Tipe Data | Constraint | Deskripsi |
|-------|-----------|-----------|-----------|
| `id` | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | ID hasil |
| `consultation_id` | UUID | NOT NULL, FK | Referensi ke consultations |
| `diagnosis` | TEXT | NOT NULL | Diagnosis hasil konsultasi |
| `severity_level` | ENUM('mild', 'moderate', 'severe') | NULL | Tingkat keparahan |
| `recommendations` | TEXT[] | NOT NULL | Array rekomendasi perawatan |
| `lifestyle_advice` | TEXT[] | NULL | Array saran gaya hidup |
| `diet_recommendation` | TEXT[] | NULL | Array rekomendasi diet |
| `follow_up_date` | DATE | NULL | Tanggal follow-up |
| `created_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Dibuat tanggal |
| `updated_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Diupdate tanggal |

---

### Tabel: `skin_condition_analysis`
**Fungsi:** Detail analisis kondisi kulit dari konsultasi  
**Primary Key:** `id`  
**Foreign Key:** `consultation_result_id` → `consultation_results(id)`

| Kolom | Tipe Data | Constraint | Deskripsi |
|-------|-----------|-----------|-----------|
| `id` | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | ID analisis |
| `consultation_result_id` | UUID | NOT NULL, FK | Referensi ke consultation_results |
| `analysis_type` | VARCHAR(100) | NOT NULL | Jenis analisis (texture, hydration, sensitivity, etc) |
| `findings` | TEXT | NOT NULL | Temuan detail |
| `score` | DECIMAL(3, 2) | NULL | Skor (0-1) |
| `image_url` | TEXT | NULL | URL foto analisis |
| `created_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Dibuat tanggal |

---

### Tabel: `product_recs`
**Fungsi:** Rekomendasi produk berdasarkan hasil konsultasi  
**Primary Key:** `id`  
**Foreign Key:** `consultation_result_id` → `consultation_results(id)`, `product_id` → `products(id)`

| Kolom | Tipe Data | Constraint | Deskripsi |
|-------|-----------|-----------|-----------|
| `id` | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | ID rekomendasi |
| `consultation_result_id` | UUID | NOT NULL, FK | Referensi ke consultation_results |
| `product_id` | UUID | NOT NULL, FK | Referensi ke products |
| `reason` | TEXT | NOT NULL | Alasan rekomendasi |
| `priority` | ENUM('high', 'medium', 'low') | NOT NULL | Prioritas rekomendasi |
| `step_order` | INTEGER | NOT NULL | Urutan langkah penggunaan |
| `usage_frequency` | VARCHAR(100) | NULL | Frekuensi penggunaan (daily, 2x week, etc) |
| `expected_benefit` | TEXT | NULL | Manfaat yang diharapkan |
| `created_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Dibuat tanggal |

---

### Tabel: `user_feedbacks`
**Fungsi:** Feedback/review dari pengguna terhadap produk/artikel  
**Primary Key:** `id`  
**Foreign Key:** `user_id` → `users(id)`, `product_id` → `products(id)`

| Kolom | Tipe Data | Constraint | Deskripsi |
|-------|-----------|-----------|-----------|
| `id` | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | ID feedback |
| `user_id` | UUID | NOT NULL, FK | Referensi ke users |
| `product_id` | UUID | NOT NULL, FK | Referensi ke products |
| `rating` | INTEGER | NOT NULL | Rating 1-5 bintang |
| `title` | VARCHAR(255) | NULL | Judul review |
| `comment` | TEXT | NOT NULL | Isi feedback |
| `helpful_count` | INTEGER | NOT NULL, DEFAULT 0 | Berapa yang membantu |
| `is_verified_purchase` | BOOLEAN | NOT NULL, DEFAULT false | Verified purchase |
| `is_approved` | BOOLEAN | NOT NULL, DEFAULT false | Approval status |
| `created_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Dibuat tanggal |
| `updated_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Diupdate tanggal |
| `deleted_at` | TIMESTAMP WITH TIME ZONE | NULL | Soft delete |

---

### Tabel: `admin_logs`
**Fungsi:** Log aktivitas admin untuk audit trail  
**Primary Key:** `id`  
**Foreign Key:** `admin_id` → `users(id)`

| Kolom | Tipe Data | Constraint | Deskripsi |
|-------|-----------|-----------|-----------|
| `id` | UUID | PRIMARY KEY, DEFAULT gen_random_uuid() | ID log |
| `admin_id` | UUID | NOT NULL, FK | Admin yang melakukan aksi |
| `action` | VARCHAR(100) | NOT NULL | Jenis aksi (create, update, delete, etc) |
| `entity_type` | VARCHAR(100) | NOT NULL | Tipe entitas (article, product, user, etc) |
| `entity_id` | UUID | NULL | ID entitas yang dimodifikasi |
| `old_values` | JSONB | NULL | Nilai lama (untuk audit) |
| `new_values` | JSONB | NULL | Nilai baru (untuk audit) |
| `ip_address` | INET | NULL | IP address admin |
| `user_agent` | TEXT | NULL | User agent browser |
| `description` | TEXT | NULL | Deskripsi aksi |
| `created_at` | TIMESTAMP WITH TIME ZONE | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Dibuat tanggal |

---

## 3. Relasi Database

### Daftar Relasi:

| Dari Tabel | Ke Tabel | Tipe | Keterangan |
|-----------|----------|------|-----------|
| users | user_profiles | 1:1 | Satu user satu profil |
| users | articles | 1:N | Satu user banyak artikel |
| users | consultations | 1:N | Satu user banyak konsultasi |
| users | user_feedbacks | 1:N | Satu user banyak feedback |
| users | admin_logs | 1:N | Satu admin banyak log |
| skin_types | user_profiles | 1:N | Satu tipe kulit banyak profil user |
| skin_types | products | 1:N | Satu tipe kulit banyak produk |
| articles | article_tags | 1:N | Satu artikel banyak tag |
| products | product_brands | M:N | Produk punya banyak brand (via junction) |
| brands | product_brands | 1:N | Satu brand banyak relasi produk |
| products | user_feedbacks | 1:N | Satu produk banyak feedback |
| consultations | consultation_results | 1:1 | Satu konsultasi satu hasil |
| consultation_results | skin_condition_analysis | 1:N | Satu hasil banyak analisis kondisi |
| consultation_results | product_recs | 1:N | Satu hasil banyak rekomendasi produk |
| products | product_recs | 1:N | Satu produk banyak rekomendasi |

### Detail Foreign Key:

```sql
-- Users to User Profiles (1:1)
ALTER TABLE user_profiles 
ADD CONSTRAINT fk_user_profiles_user_id 
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- User Profiles to Skin Types (M:1)
ALTER TABLE user_profiles 
ADD CONSTRAINT fk_user_profiles_skin_type_id 
FOREIGN KEY (skin_type_id) REFERENCES skin_types(id) ON DELETE SET NULL;

-- Articles to Users (M:1)
ALTER TABLE articles 
ADD CONSTRAINT fk_articles_created_by 
FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT;

-- Article Tags to Articles (M:1)
ALTER TABLE article_tags 
ADD CONSTRAINT fk_article_tags_article_id 
FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE;

-- Products to Skin Types (M:1)
ALTER TABLE products 
ADD CONSTRAINT fk_products_skin_type_id 
FOREIGN KEY (skin_type_id) REFERENCES skin_types(id) ON DELETE RESTRICT;

-- Product Brands (M:N Junction)
ALTER TABLE product_brands 
ADD CONSTRAINT fk_product_brands_product_id 
FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE;

ALTER TABLE product_brands 
ADD CONSTRAINT fk_product_brands_brand_id 
FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE CASCADE;

-- Consultations to Users (M:1)
ALTER TABLE consultations 
ADD CONSTRAINT fk_consultations_user_id 
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE consultations 
ADD CONSTRAINT fk_consultations_skin_type_id 
FOREIGN KEY (skin_type_detected) REFERENCES skin_types(id) ON DELETE SET NULL;

-- Consultation Results to Consultations (1:1)
ALTER TABLE consultation_results 
ADD CONSTRAINT fk_consultation_results_consultation_id 
FOREIGN KEY (consultation_id) REFERENCES consultations(id) ON DELETE CASCADE;

-- Skin Condition Analysis to Consultation Results (M:1)
ALTER TABLE skin_condition_analysis 
ADD CONSTRAINT fk_skin_analysis_result_id 
FOREIGN KEY (consultation_result_id) REFERENCES consultation_results(id) ON DELETE CASCADE;

-- Product Recs to Consultation Results (M:1)
ALTER TABLE product_recs 
ADD CONSTRAINT fk_product_recs_result_id 
FOREIGN KEY (consultation_result_id) REFERENCES consultation_results(id) ON DELETE CASCADE;

-- Product Recs to Products (M:1)
ALTER TABLE product_recs 
ADD CONSTRAINT fk_product_recs_product_id 
FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT;

-- User Feedbacks to Users (M:1)
ALTER TABLE user_feedbacks 
ADD CONSTRAINT fk_feedbacks_user_id 
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- User Feedbacks to Products (M:1)
ALTER TABLE user_feedbacks 
ADD CONSTRAINT fk_feedbacks_product_id 
FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE;

-- Admin Logs to Users (M:1)
ALTER TABLE admin_logs 
ADD CONSTRAINT fk_admin_logs_admin_id 
FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE;
```

---

## 4. Normalisasi

### Analisis Normalisasi (1NF → 3NF → BCNF):

#### **First Normal Form (1NF):** ✅
- ✓ Semua kolom berisi atomic values (tidak ada array, kecuali TEXT[])
- ✓ Tidak ada repeating groups
- ✓ Setiap baris identik dengan primary key
- ℹ️ Array digunakan untuk performa (PostgreSQL native support)

#### **Second Normal Form (2NF):** ✅
- ✓ Sudah 1NF
- ✓ Tidak ada partial dependencies
- ✓ Setiap non-key attribute fully dependent pada primary key
- ✓ Contoh: `user_profiles.skin_concerns` hanya bergantung pada `user_id`

#### **Third Normal Form (3NF):** ✅
- ✓ Sudah 2NF
- ✓ Tidak ada transitive dependencies
- ✓ Contoh: Products tergantung pada Skin Types via `skin_type_id` (bukan embedded)
- ✓ Brands dipisah dari Products via junction table

#### **Boyce-Codd Normal Form (BCNF):** ✅
- ✓ Lebih ketat dari 3NF
- ✓ Setiap determinant adalah candidate key
- ✓ Semua tabel memenuhi criteria BCNF

### Desain Efisien:

```
❌ TIDAK EFISIEN (Denormalisasi berlebihan):
products: {
  id, name, brand_name, brand_country, brand_website ...
  → Redundansi data brand
}

✅ EFISIEN (Normalized):
products: { id, name, ... }
brands: { id, name, country, website ... }
product_brands: { product_id, brand_id }
  → Single source of truth untuk setiap entitas
```

---

## 5. Constraints & Indexing

### A. Constraints

#### **PRIMARY KEY:**
```sql
-- Semua tabel menggunakan UUID sebagai PK
-- Contoh: ALTER TABLE users ADD PRIMARY KEY (id);
```

#### **UNIQUE Constraints:**
```sql
-- Users
ALTER TABLE users ADD CONSTRAINT unique_email UNIQUE (email);

-- User Profiles
ALTER TABLE user_profiles ADD CONSTRAINT unique_user_id UNIQUE (user_id);

-- Articles
ALTER TABLE articles ADD CONSTRAINT unique_article_slug UNIQUE (slug);

-- Products
ALTER TABLE products ADD CONSTRAINT unique_sku UNIQUE (sku);
ALTER TABLE products ADD CONSTRAINT unique_product_slug UNIQUE (slug);

-- Brands
ALTER TABLE brands ADD CONSTRAINT unique_brand_name UNIQUE (name);
ALTER TABLE brands ADD CONSTRAINT unique_brand_slug UNIQUE (slug);

-- Skin Types
ALTER TABLE skin_types ADD CONSTRAINT unique_skin_type_name UNIQUE (name);
ALTER TABLE skin_types ADD CONSTRAINT unique_skin_type_slug UNIQUE (slug);
```

#### **NOT NULL Constraints:**
Sudah didefinisikan di Data Dictionary (kolom-kolom kritis)

#### **CHECK Constraints:**
```sql
-- Products: Harga valid
ALTER TABLE products ADD CONSTRAINT check_price_valid 
  CHECK (price > 0 AND (discount_price IS NULL OR discount_price > 0));
ALTER TABLE products ADD CONSTRAINT check_stock_non_negative 
  CHECK (stock_quantity >= 0);

-- User Feedbacks: Rating 1-5
ALTER TABLE user_feedbacks ADD CONSTRAINT check_rating_range 
  CHECK (rating >= 1 AND rating <= 5);

-- Consultations: Status valid
ALTER TABLE consultations ADD CONSTRAINT check_valid_status 
  CHECK (status IN ('pending', 'in_progress', 'completed', 'archived'));

-- Confidence Score: 0-1
ALTER TABLE consultations ADD CONSTRAINT check_confidence_score 
  CHECK (confidence_score >= 0 AND confidence_score <= 1);
```

#### **DEFAULT Values:**
```sql
-- UUID Generation
DEFAULT gen_random_uuid()

-- Timestamps
DEFAULT CURRENT_TIMESTAMP

-- Boolean
DEFAULT true / DEFAULT false

-- Enums
DEFAULT 'user' -- untuk role
DEFAULT 0 -- untuk counters
```

---

### B. Indexing Strategy

#### **Performance-Critical Indexes:**

```sql
-- 1. USERS (Authentication & Search)
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role, is_active);
CREATE INDEX idx_users_created_at ON users(created_at DESC);

-- 2. USER PROFILES (Profile Lookup)
CREATE INDEX idx_user_profiles_user_id ON user_profiles(user_id);
CREATE INDEX idx_user_profiles_skin_type_id ON user_profiles(skin_type_id);

-- 3. ARTICLES (Content Discovery)
CREATE INDEX idx_articles_slug ON articles(slug);
CREATE INDEX idx_articles_category ON articles(category);
CREATE INDEX idx_articles_created_by ON articles(created_by);
CREATE INDEX idx_articles_is_published ON articles(is_published, published_at DESC);
CREATE INDEX idx_articles_search ON articles(title, excerpt) 
  USING GIN(to_tsvector('Indonesian', title || ' ' || excerpt));

-- 4. ARTICLE TAGS (Tag Filtering)
CREATE INDEX idx_article_tags_article_id ON article_tags(article_id);
CREATE INDEX idx_article_tags_tag_name ON article_tags(tag_name);

-- 5. PRODUCTS (Katalog & Search)
CREATE INDEX idx_products_sku ON products(sku);
CREATE INDEX idx_products_slug ON products(slug);
CREATE INDEX idx_products_skin_type_id ON products(skin_type_id);
CREATE INDEX idx_products_is_active ON products(is_active);
CREATE INDEX idx_products_search ON products(name, description) 
  USING GIN(to_tsvector('Indonesian', name || ' ' || description));
CREATE INDEX idx_products_price ON products(price);
CREATE INDEX idx_products_rating ON products(rating DESC);

-- 6. PRODUCT BRANDS (M:N Lookup)
CREATE INDEX idx_product_brands_product_id ON product_brands(product_id);
CREATE INDEX idx_product_brands_brand_id ON product_brands(brand_id);

-- 7. BRANDS (Brand Filter)
CREATE INDEX idx_brands_slug ON brands(slug);
CREATE INDEX idx_brands_is_active ON brands(is_active);

-- 8. CONSULTATIONS (User History)
CREATE INDEX idx_consultations_user_id ON consultations(user_id);
CREATE INDEX idx_consultations_status ON consultations(status);
CREATE INDEX idx_consultations_date ON consultations(consultation_date DESC);
CREATE INDEX idx_consultations_skin_type ON consultations(skin_type_detected);

-- 9. CONSULTATION RESULTS (Quick Lookup)
CREATE INDEX idx_consultation_results_consultation_id ON consultation_results(consultation_id);

-- 10. SKIN CONDITION ANALYSIS (Analysis Lookup)
CREATE INDEX idx_skin_analysis_consultation_result ON skin_condition_analysis(consultation_result_id);

-- 11. PRODUCT RECS (Recommendation Lookup)
CREATE INDEX idx_product_recs_consultation_result ON product_recs(consultation_result_id);
CREATE INDEX idx_product_recs_product_id ON product_recs(product_id);

-- 12. USER FEEDBACKS (Review & Rating)
CREATE INDEX idx_feedbacks_user_id ON user_feedbacks(user_id);
CREATE INDEX idx_feedbacks_product_id ON user_feedbacks(product_id);
CREATE INDEX idx_feedbacks_is_approved ON user_feedbacks(is_approved, created_at DESC);
CREATE INDEX idx_feedbacks_rating ON user_feedbacks(rating DESC);

-- 13. ADMIN LOGS (Audit Trail)
CREATE INDEX idx_admin_logs_admin_id ON admin_logs(admin_id);
CREATE INDEX idx_admin_logs_entity ON admin_logs(entity_type, entity_id);
CREATE INDEX idx_admin_logs_action ON admin_logs(action, created_at DESC);
```

#### **Full-Text Search Index (PostgreSQL):**

```sql
-- Untuk pencarian artikel yang lebih baik
CREATE INDEX idx_articles_fulltext ON articles 
  USING GIN(to_tsvector('Indonesian', coalesce(title, '') || ' ' || coalesce(content, '')));

-- Untuk pencarian produk
CREATE INDEX idx_products_fulltext ON products 
  USING GIN(to_tsvector('Indonesian', coalesce(name, '') || ' ' || coalesce(description, '')));
```

#### **Composite Indexes (Multi-column):**

```sql
-- User login tracking
CREATE INDEX idx_users_role_active_login ON users(role, is_active, last_login_at DESC);

-- Article filtering & sorting
CREATE INDEX idx_articles_category_published ON articles(category, is_published, published_at DESC);

-- Product filtering
CREATE INDEX idx_products_type_skin_price ON products(product_type, skin_type_id, price);

-- Feedback approval & sorting
CREATE INDEX idx_feedbacks_approved_rating ON user_feedbacks(is_approved, rating DESC, created_at DESC);
```

---

## 6. Script SQL

### 6.1 DDL (Data Definition Language) - Buat Tabel

```sql
-- ============================================================
-- SKINQUO DATABASE SCHEMA - PostgreSQL
-- ============================================================

-- Enable UUID Extension
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- ============================================================
-- 1. USERS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    is_active BOOLEAN NOT NULL DEFAULT true,
    last_login_at TIMESTAMP WITH TIME ZONE,
    email_verified_at TIMESTAMP WITH TIME ZONE,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP WITH TIME ZONE
);

CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role, is_active);
CREATE INDEX idx_users_created_at ON users(created_at DESC);

-- ============================================================
-- 2. SKIN_TYPES TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS skin_types (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(50) NOT NULL UNIQUE,
    slug VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    characteristics TEXT[],
    icon_url TEXT,
    color_hex VARCHAR(7),
    is_active BOOLEAN NOT NULL DEFAULT true,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_skin_types_slug ON skin_types(slug);
CREATE INDEX idx_skin_types_is_active ON skin_types(is_active);

-- ============================================================
-- 3. USER_PROFILES TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS user_profiles (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID NOT NULL UNIQUE,
    phone VARCHAR(20),
    date_of_birth DATE,
    gender ENUM('male', 'female', 'other'),
    skin_type_id UUID,
    avatar_url TEXT,
    bio TEXT,
    address TEXT,
    city VARCHAR(100),
    province VARCHAR(100),
    postal_code VARCHAR(10),
    skin_concerns TEXT[],
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (skin_type_id) REFERENCES skin_types(id) ON DELETE SET NULL
);

CREATE INDEX idx_user_profiles_user_id ON user_profiles(user_id);
CREATE INDEX idx_user_profiles_skin_type_id ON user_profiles(skin_type_id);

-- ============================================================
-- 4. ARTICLES TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS articles (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt VARCHAR(500) NOT NULL,
    content TEXT NOT NULL,
    featured_image TEXT,
    thumbnail TEXT,
    category VARCHAR(100) NOT NULL,
    author VARCHAR(255) NOT NULL,
    created_by UUID NOT NULL,
    view_count INTEGER NOT NULL DEFAULT 0,
    published_at TIMESTAMP WITH TIME ZONE,
    is_published BOOLEAN NOT NULL DEFAULT false,
    meta_description VARCHAR(160),
    meta_keywords VARCHAR(255),
    reading_time INTEGER,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP WITH TIME ZONE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT
);

CREATE INDEX idx_articles_slug ON articles(slug);
CREATE INDEX idx_articles_category ON articles(category);
CREATE INDEX idx_articles_created_by ON articles(created_by);
CREATE INDEX idx_articles_is_published ON articles(is_published, published_at DESC);
CREATE INDEX idx_articles_search ON articles(title, excerpt) 
    USING GIN(to_tsvector('Indonesian', title || ' ' || excerpt));

-- ============================================================
-- 5. ARTICLE_TAGS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS article_tags (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    article_id UUID NOT NULL,
    tag_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
);

CREATE INDEX idx_article_tags_article_id ON article_tags(article_id);
CREATE INDEX idx_article_tags_tag_name ON article_tags(tag_name);

-- ============================================================
-- 6. BRANDS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS brands (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(255) NOT NULL UNIQUE,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    logo_url TEXT,
    website VARCHAR(255),
    country VARCHAR(100),
    is_active BOOLEAN NOT NULL DEFAULT true,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_brands_slug ON brands(slug);
CREATE INDEX idx_brands_is_active ON brands(is_active);

-- ============================================================
-- 7. PRODUCTS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS products (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    sku VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    price DECIMAL(12, 2) NOT NULL,
    discount_price DECIMAL(12, 2),
    stock_quantity INTEGER NOT NULL DEFAULT 0,
    skin_type_id UUID NOT NULL,
    product_type VARCHAR(100) NOT NULL,
    benefits TEXT[],
    ingredients TEXT[],
    usage_instruction TEXT,
    main_image TEXT,
    gallery_images TEXT[],
    rating DECIMAL(3, 2) NOT NULL DEFAULT 0,
    total_reviews INTEGER NOT NULL DEFAULT 0,
    is_active BOOLEAN NOT NULL DEFAULT true,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP WITH TIME ZONE,
    FOREIGN KEY (skin_type_id) REFERENCES skin_types(id) ON DELETE RESTRICT,
    CHECK (price > 0),
    CHECK (discount_price IS NULL OR discount_price > 0),
    CHECK (stock_quantity >= 0),
    CHECK (rating >= 0 AND rating <= 5)
);

CREATE INDEX idx_products_sku ON products(sku);
CREATE INDEX idx_products_slug ON products(slug);
CREATE INDEX idx_products_skin_type_id ON products(skin_type_id);
CREATE INDEX idx_products_is_active ON products(is_active);
CREATE INDEX idx_products_search ON products(name, description) 
    USING GIN(to_tsvector('Indonesian', name || ' ' || description));
CREATE INDEX idx_products_price ON products(price);
CREATE INDEX idx_products_rating ON products(rating DESC);

-- ============================================================
-- 8. PRODUCT_BRANDS TABLE (M:N Junction)
-- ============================================================
CREATE TABLE IF NOT EXISTS product_brands (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    product_id UUID NOT NULL,
    brand_id UUID NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE CASCADE,
    UNIQUE(product_id, brand_id)
);

CREATE INDEX idx_product_brands_product_id ON product_brands(product_id);
CREATE INDEX idx_product_brands_brand_id ON product_brands(brand_id);

-- ============================================================
-- 9. CONSULTATIONS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS consultations (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID NOT NULL,
    consultation_date TIMESTAMP WITH TIME ZONE NOT NULL,
    skin_type_detected UUID,
    status ENUM('pending', 'in_progress', 'completed', 'archived') NOT NULL DEFAULT 'pending',
    notes TEXT,
    confidence_score DECIMAL(3, 2),
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (skin_type_detected) REFERENCES skin_types(id) ON DELETE SET NULL,
    CHECK (confidence_score >= 0 AND confidence_score <= 1)
);

CREATE INDEX idx_consultations_user_id ON consultations(user_id);
CREATE INDEX idx_consultations_status ON consultations(status);
CREATE INDEX idx_consultations_date ON consultations(consultation_date DESC);
CREATE INDEX idx_consultations_skin_type ON consultations(skin_type_detected);

-- ============================================================
-- 10. CONSULTATION_RESULTS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS consultation_results (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    consultation_id UUID NOT NULL UNIQUE,
    diagnosis TEXT NOT NULL,
    severity_level ENUM('mild', 'moderate', 'severe'),
    recommendations TEXT[] NOT NULL,
    lifestyle_advice TEXT[],
    diet_recommendation TEXT[],
    follow_up_date DATE,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (consultation_id) REFERENCES consultations(id) ON DELETE CASCADE
);

CREATE INDEX idx_consultation_results_consultation_id ON consultation_results(consultation_id);

-- ============================================================
-- 11. SKIN_CONDITION_ANALYSIS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS skin_condition_analysis (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    consultation_result_id UUID NOT NULL,
    analysis_type VARCHAR(100) NOT NULL,
    findings TEXT NOT NULL,
    score DECIMAL(3, 2),
    image_url TEXT,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (consultation_result_id) REFERENCES consultation_results(id) ON DELETE CASCADE,
    CHECK (score IS NULL OR (score >= 0 AND score <= 1))
);

CREATE INDEX idx_skin_analysis_consultation_result ON skin_condition_analysis(consultation_result_id);

-- ============================================================
-- 12. PRODUCT_RECS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS product_recs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    consultation_result_id UUID NOT NULL,
    product_id UUID NOT NULL,
    reason TEXT NOT NULL,
    priority ENUM('high', 'medium', 'low') NOT NULL,
    step_order INTEGER NOT NULL,
    usage_frequency VARCHAR(100),
    expected_benefit TEXT,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (consultation_result_id) REFERENCES consultation_results(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
);

CREATE INDEX idx_product_recs_consultation_result ON product_recs(consultation_result_id);
CREATE INDEX idx_product_recs_product_id ON product_recs(product_id);

-- ============================================================
-- 13. USER_FEEDBACKS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS user_feedbacks (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID NOT NULL,
    product_id UUID NOT NULL,
    rating INTEGER NOT NULL,
    title VARCHAR(255),
    comment TEXT NOT NULL,
    helpful_count INTEGER NOT NULL DEFAULT 0,
    is_verified_purchase BOOLEAN NOT NULL DEFAULT false,
    is_approved BOOLEAN NOT NULL DEFAULT false,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP WITH TIME ZONE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CHECK (rating >= 1 AND rating <= 5)
);

CREATE INDEX idx_feedbacks_user_id ON user_feedbacks(user_id);
CREATE INDEX idx_feedbacks_product_id ON user_feedbacks(product_id);
CREATE INDEX idx_feedbacks_is_approved ON user_feedbacks(is_approved, created_at DESC);
CREATE INDEX idx_feedbacks_rating ON user_feedbacks(rating DESC);

-- ============================================================
-- 14. ADMIN_LOGS TABLE
-- ============================================================
CREATE TABLE IF NOT EXISTS admin_logs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    admin_id UUID NOT NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(100) NOT NULL,
    entity_id UUID,
    old_values JSONB,
    new_values JSONB,
    ip_address INET,
    user_agent TEXT,
    description TEXT,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX idx_admin_logs_admin_id ON admin_logs(admin_id);
CREATE INDEX idx_admin_logs_entity ON admin_logs(entity_type, entity_id);
CREATE INDEX idx_admin_logs_action ON admin_logs(action, created_at DESC);

-- ============================================================
-- SEED DATA - Master Data
-- ============================================================

-- Insert Skin Types
INSERT INTO skin_types (name, slug, description, characteristics, color_hex) VALUES
('Normal', 'normal', 'Kulit seimbang dengan pori-pori normal', ARRAY['Balanced', 'Small pores', 'Soft texture'], '#E8C9A0'),
('Oily', 'oily', 'Kulit berminyak dengan pori-pori besar', ARRAY['Shiny', 'Large pores', 'Prone to acne'], '#D4A574'),
('Dry', 'dry', 'Kulit kering dan sering terasa ketat', ARRAY['Flaky', 'Rough texture', 'Need moisture'], '#C9A985'),
('Combination', 'combination', 'Kulit berminyak di T-zone, kering di area lain', ARRAY['Mixed characteristics', 'Variable texture'], '#D9B896'),
('Sensitive', 'sensitive', 'Kulit sensitif mudah iritasi', ARRAY['Easily irritated', 'Reactive', 'Needs care'], '#E0C9B8')
ON CONFLICT DO NOTHING;
```

---

### 6.2 Migrasi Laravel (Jika menggunakan Laravel)

Buat file migration di `database/migrations/`:

```php
// database/migrations/2026_04_13_000001_create_skinquo_schema.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Extension setup (run raw SQL)
        DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
        DB::statement('CREATE EXTENSION IF NOT EXISTS "pgcrypto"');

        // Users
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('email');
            $table->index(['role', 'is_active']);
            $table->index('created_at');
        });

        // Skin Types
        Schema::create('skin_types', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('characteristics')->nullable();
            $table->string('icon_url')->nullable();
            $table->string('color_hex', 7)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('slug');
            $table->index('is_active');
        });

        // User Profiles
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('user_id')->unique();
            $table->string('phone', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->uuid('skin_type_id')->nullable();
            $table->text('avatar_url')->nullable();
            $table->text('bio')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
            $table->json('skin_concerns')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('skin_type_id')->references('id')->on('skin_types')->onDelete('setNull');
            $table->index('skin_type_id');
        });

        // Articles
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt');
            $table->longText('content');
            $table->text('featured_image')->nullable();
            $table->text('thumbnail')->nullable();
            $table->string('category');
            $table->string('author');
            $table->uuid('created_by');
            $table->integer('view_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_published')->default(false);
            $table->string('meta_description', 160)->nullable();
            $table->string('meta_keywords')->nullable();
            $table->integer('reading_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->index('slug');
            $table->index('category');
            $table->index(['is_published', 'published_at']);
        });

        // Continue dengan tabel lainnya...
    }

    public function down(): void
    {
        // Drop all tables in reverse order
        Schema::dropIfExists('admin_logs');
        Schema::dropIfExists('user_feedbacks');
        Schema::dropIfExists('product_recs');
        Schema::dropIfExists('skin_condition_analysis');
        Schema::dropIfExists('consultation_results');
        Schema::dropIfExists('consultations');
        Schema::dropIfExists('product_brands');
        Schema::dropIfExists('products');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('article_tags');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('skin_types');
        Schema::dropIfExists('users');
    }
};
```

---

## 📝 Ringkasan Perancangan

### ✅ Fitur Utama:
- **13 Tabel Terstruktur** dengan normalisasi 3NF/BCNF
- **UUID Primary Keys** untuk scalability
- **Foreign Keys** dengan proper CASCADE rules
- **ENUM Types** untuk PostgreSQL strict typing
- **Full-Text Search** untuk artikel dan produk
- **Composite Indexes** untuk query optimization
- **JSONB Fields** untuk flexible data (tags, characteristics)
- **Soft Deletes** untuk data integrity
- **Audit Trail** melalui admin_logs

### 🔒 Security & Integrity:
- ✅ CHECK constraints untuk validasi data
- ✅ UNIQUE constraints untuk data consistency
- ✅ Foreign keys dengan proper cascade behavior
- ✅ Encrypted passwords (bcrypt)
- ✅ IP tracking untuk admin activities

### 📊 Performance Optimization:
- ✅ 28+ Strategic indexes
- ✅ Full-text search dengan Indonesian language
- ✅ Composite indexes untuk multi-column queries
- ✅ Proper query planning dengan ANALYZE

### 🚀 Scalability:
- ✅ UUID untuk distributed systems
- ✅ Denormalisasi minimal (hanya for counters)
- ✅ Partition-ready structure
- ✅ M:N relations via proper junction tables

---

## 7. Fitur Admin - Mapping Database

Berikut penjelasan bagaimana database design mendukung **semua fitur admin**:

### ✅ 1. Login & Logout (seperti User Biasa)

**Tabel yang digunakan:** `users`

```sql
-- Admin login dengan email & password
SELECT * FROM users 
WHERE email = 'admin@skinquo.com' 
  AND role = 'admin' 
  AND is_active = true;

-- Update last login time
UPDATE users 
SET last_login_at = CURRENT_TIMESTAMP 
WHERE id = ?;

-- Soft logout (hanya UI, tidak perlu hapus session)
-- Session/token dihandle di application layer
```

**Kolom yang digunakan:**
- `id` - Identifikasi unik admin
- `email` - Email login
- `password` - Password terenkripsi bcrypt
- `role = 'admin'` - Membedakan admin dari user biasa
- `is_active` - Bisa disable akun admin tanpa delete
- `last_login_at` - Track aktivitas login terakhir
- `created_at` - Audit kapan akun dibuat

**Index:** `idx_users_email` & `idx_users_role` untuk query login cepat

---

### ✅ 2. Kelola Profil Admin (Ubah Username, Password, Info)

**Tabel yang digunakan:** `users` + `user_profiles`

```sql
-- View profil admin
SELECT u.*, up.* 
FROM users u 
LEFT JOIN user_profiles up ON u.id = up.user_id
WHERE u.id = ? AND u.role = 'admin';

-- Update nama/username admin
UPDATE users 
SET name = ?, updated_at = CURRENT_TIMESTAMP 
WHERE id = ? AND role = 'admin';

-- Update password admin (bcrypt hash)
UPDATE users 
SET password = bcrypt(?), updated_at = CURRENT_TIMESTAMP 
WHERE id = ? AND role = 'admin';

-- Update profil lengkap (opsional)
UPDATE user_profiles 
SET avatar_url = ?, bio = ?, phone = ?, address = ?, 
    city = ?, province = ?, postal_code = ?, updated_at = CURRENT_TIMESTAMP 
WHERE user_id = ?;
```

**Struktur untuk admin profile:**

```
ADMIN (role = 'admin')
├── users.name          ← Nama admin
├── users.email         ← Email admin
├── users.password      ← Password terenkripsi
├── users.role          ← 'admin'
├── users.is_active     ← Status aktif/nonaktif
└── user_profiles       ← Extended profile (opsional)
    ├── avatar_url
    ├── bio
    ├── phone
    ├── address
    ├── city
    ├── province
    └── postal_code
```

**Fitur Tambahan:**
- ✅ Soft delete dengan `deleted_at` jika perlu disable account
- ✅ Tracking `updated_at` untuk audit trail
- ✅ `email_verified_at` untuk security

---

### ✅ 3. CRUD Produk Skincare

**Tabel Utama:** `products`  
**Tabel Support:** `brands`, `product_brands`, `skin_types`

#### **CREATE (Buat Produk Baru):**

```sql
-- Insert produk baru
INSERT INTO products (
    id, name, slug, sku, description, price, discount_price,
    stock_quantity, skin_type_id, product_type, benefits, 
    ingredients, usage_instruction, main_image, gallery_images,
    is_active, created_at, updated_at
) VALUES (
    gen_random_uuid(),
    'Cetaphil Gentle Cleanser',
    'cetaphil-gentle-cleanser',
    'CETH-001',
    'Pembersih wajah lembut...',
    150000.00,
    NULL,
    100,
    (SELECT id FROM skin_types WHERE slug = 'sensitive'),
    'Cleanser',
    ARRAY['Gentle', 'Hydrating', 'Non-irritating'],
    ARRAY['Cetyl Alcohol', 'Glycerin', 'Water'],
    'Gunakan 2x sehari...',
    'https://...',
    ARRAY['https://...', 'https://...'],
    true,
    CURRENT_TIMESTAMP,
    CURRENT_TIMESTAMP
);

-- Link dengan brand (M:N)
INSERT INTO product_brands (product_id, brand_id, created_at)
VALUES (?, ?, CURRENT_TIMESTAMP);
```

**Kolom yang admin input:**
- `name` - Nama produk
- `slug` - Auto-generate dari name atau admin input
- `sku` - Kode unik stok
- `description` - Deskripsi detail
- `price` - Harga normal
- `discount_price` - Harga promo (opsional)
- `stock_quantity` - Jumlah stok
- `skin_type_id` - Tipe kulit yang cocok (dropdown dari skin_types)
- `product_type` - Tipe produk: Cleanser, Toner, Serum, Moisturizer, Sunscreen, Mask, dll
- `benefits` - Array manfaat
- `ingredients` - Array bahan utama
- `usage_instruction` - Cara pakai
- `main_image` - Gambar utama
- `gallery_images` - Array galeri gambar
- `is_active` - Aktif/nonaktif

#### **READ (Lihat Produk):**

```sql
-- List semua produk dengan filter
SELECT p.*, s.name as skin_type_name, COUNT(DISTINCT uf.id) as total_reviews,
       ARRAY_AGG(DISTINCT b.name) as brand_names
FROM products p
LEFT JOIN skin_types s ON p.skin_type_id = s.id
LEFT JOIN user_feedbacks uf ON p.id = uf.product_id AND uf.is_approved = true
LEFT JOIN product_brands pb ON p.id = pb.product_id
LEFT JOIN brands b ON pb.brand_id = b.id
WHERE p.deleted_at IS NULL
GROUP BY p.id, s.name
ORDER BY p.created_at DESC
LIMIT 20 OFFSET 0;

-- Detail produk single
SELECT p.*, s.name as skin_type_name,
       ARRAY_AGG(DISTINCT b.name) as brands,
       COUNT(DISTINCT uf.id) as total_reviews,
       AVG(uf.rating) as avg_rating
FROM products p
LEFT JOIN skin_types s ON p.skin_type_id = s.id
LEFT JOIN product_brands pb ON p.id = pb.product_id
LEFT JOIN brands b ON pb.brand_id = b.id
LEFT JOIN user_feedbacks uf ON p.id = uf.product_id AND uf.is_approved = true
WHERE p.id = ? AND p.deleted_at IS NULL
GROUP BY p.id, s.name;
```

#### **UPDATE (Edit Produk):**

```sql
-- Update basic info
UPDATE products 
SET name = ?, slug = ?, description = ?, price = ?, discount_price = ?,
    stock_quantity = ?, product_type = ?, benefits = ?, ingredients = ?,
    usage_instruction = ?, main_image = ?, gallery_images = ?, 
    is_active = ?, updated_at = CURRENT_TIMESTAMP
WHERE id = ? AND deleted_at IS NULL;

-- Update stock
UPDATE products 
SET stock_quantity = ?, updated_at = CURRENT_TIMESTAMP
WHERE id = ? AND deleted_at IS NULL;

-- Update harga/diskon
UPDATE products 
SET price = ?, discount_price = ?, updated_at = CURRENT_TIMESTAMP
WHERE id = ? AND deleted_at IS NULL;
```

#### **DELETE (Hapus Produk - Soft Delete):**

```sql
-- Soft delete (tidak hapus data, hanya tandai deleted_at)
UPDATE products 
SET deleted_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP
WHERE id = ? AND deleted_at IS NULL;

-- Restore produk (jika perlu)
UPDATE products 
SET deleted_at = NULL, updated_at = CURRENT_TIMESTAMP
WHERE id = ?;

-- Permanent delete (hati-hati!)
DELETE FROM products WHERE id = ?;
```

**Index untuk performa CRUD:**
- `idx_products_sku` - Cek SKU unique
- `idx_products_slug` - Cek slug unique
- `idx_products_is_active` - Filter aktif/nonaktif
- `idx_products_price` - Sort by price
- `idx_products_rating` - Sort by rating

---

### ✅ 4. CRUD Skin Guide (Articles)

**Tabel Utama:** `articles`  
**Tabel Support:** `article_tags`

#### **CREATE (Buat Artikel):**

```sql
INSERT INTO articles (
    id, title, slug, excerpt, content, featured_image, thumbnail,
    category, author, created_by, is_published, published_at,
    meta_description, meta_keywords, reading_time, created_at, updated_at
) VALUES (
    gen_random_uuid(),
    'Cara Merawat Kulit Sensitif dengan Produk yang Tepat',
    'cara-merawat-kulit-sensitif',
    'Tips dan trik merawat kulit sensitif tanpa iritasi...',
    '# Cara Merawat Kulit Sensitif\n\nKulit sensitif memerlukan...',
    'https://...',
    'https://...',
    'Sensitive',
    'Dr. Skin Expert',
    (SELECT id FROM users WHERE email = 'admin@skinquo.com'),
    true,
    CURRENT_TIMESTAMP,
    'Tips merawat kulit sensitif dengan produk alami dan aman',
    'kulit sensitif, perawatan, tips, skincare',
    5,
    CURRENT_TIMESTAMP,
    CURRENT_TIMESTAMP
);

-- Tambah tags
INSERT INTO article_tags (article_id, tag_name, created_at) VALUES
(?, 'Sensitive', CURRENT_TIMESTAMP),
(?, 'Care Tips', CURRENT_TIMESTAMP),
(?, 'Natural', CURRENT_TIMESTAMP);
```

**Kolom yang admin input:**
- `title` - Judul artikel
- `slug` - URL slug (auto-generate)
- `excerpt` - Ringkasan (max 500 char)
- `content` - Konten markdown lengkap
- `featured_image` - Gambar featured
- `thumbnail` - Thumbnail kecil
- `category` - Kategori: Moisturizing, Anti-Aging, Acne, Sensitive, dll
- `author` - Nama author
- `meta_description` - SEO meta description
- `meta_keywords` - SEO keywords
- `reading_time` - Estimasi waktu baca (auto-calc atau manual)
- `is_published` - Draft atau published
- `published_at` - Tanggal publikasi

#### **READ (Lihat Artikel):**

```sql
-- List artikel dengan pagination
SELECT a.*, 
       COUNT(DISTINCT at.id) as tag_count,
       (SELECT COUNT(*) FROM articles WHERE created_by = a.created_by) as author_articles
FROM articles a
LEFT JOIN article_tags at ON a.id = at.article_id
WHERE a.deleted_at IS NULL
GROUP BY a.id
ORDER BY a.created_at DESC
LIMIT 20 OFFSET 0;

-- Filter by category
SELECT * FROM articles 
WHERE category = ? AND is_published = true AND deleted_at IS NULL
ORDER BY published_at DESC;

-- Search artikel
SELECT * FROM articles 
WHERE (title ILIKE ? OR content ILIKE ?) 
  AND deleted_at IS NULL
ORDER BY created_at DESC;
```

#### **UPDATE (Edit Artikel):**

```sql
-- Update konten
UPDATE articles 
SET title = ?, slug = ?, excerpt = ?, content = ?, 
    featured_image = ?, thumbnail = ?, category = ?,
    meta_description = ?, meta_keywords = ?, reading_time = ?,
    updated_at = CURRENT_TIMESTAMP
WHERE id = ? AND deleted_at IS NULL;

-- Publish/unpublish
UPDATE articles 
SET is_published = ?, published_at = CASE 
    WHEN ? = true THEN CURRENT_TIMESTAMP 
    ELSE NULL 
END,
updated_at = CURRENT_TIMESTAMP
WHERE id = ? AND deleted_at IS NULL;

-- Update view count (real-time tracking)
UPDATE articles 
SET view_count = view_count + 1
WHERE id = ?;
```

#### **DELETE (Hapus Artikel - Soft Delete):**

```sql
-- Soft delete
UPDATE articles 
SET deleted_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP
WHERE id = ? AND deleted_at IS NULL;

-- Restore
UPDATE articles 
SET deleted_at = NULL, updated_at = CURRENT_TIMESTAMP
WHERE id = ?;
```

**Index untuk performa:**
- `idx_articles_slug` - URL lookup
- `idx_articles_category` - Filter by category
- `idx_articles_is_published` - Show/hide published articles
- `idx_articles_search` - Full-text search dengan GIN

---

### ✅ 5. Monitoring Feedback dari Pengguna

**Tabel Utama:** `user_feedbacks`  
**Tabel Support:** `products`, `users`

#### **VIEW (Lihat Feedback/Review):**

```sql
-- List semua feedback dengan approval pending
SELECT uf.*, u.name as user_name, u.email, p.name as product_name
FROM user_feedbacks uf
JOIN users u ON uf.user_id = u.id
JOIN products p ON uf.product_id = p.id
WHERE uf.is_approved = false OR uf.deleted_at IS NULL
ORDER BY uf.created_at DESC
LIMIT 50 OFFSET 0;

-- Filter by product
SELECT uf.*, u.name as user_name, p.name as product_name,
       uf.rating, uf.is_verified_purchase, uf.is_approved
FROM user_feedbacks uf
JOIN users u ON uf.user_id = u.id
JOIN products p ON uf.product_id = p.id
WHERE p.id = ? AND uf.deleted_at IS NULL
ORDER BY uf.created_at DESC;

-- Filter by rating
SELECT uf.*, u.name, p.name as product_name, COUNT(*) OVER () as total
FROM user_feedbacks uf
JOIN users u ON uf.user_id = u.id
JOIN products p ON uf.product_id = p.id
WHERE uf.rating >= ? AND uf.is_approved = true AND uf.deleted_at IS NULL
ORDER BY uf.helpful_count DESC;

-- Dashboard stats
SELECT 
    COUNT(*) as total_feedbacks,
    COUNT(CASE WHEN is_approved = false THEN 1 END) as pending_approval,
    AVG(rating) as avg_rating,
    COUNT(CASE WHEN is_verified_purchase = true THEN 1 END) as verified_purchases
FROM user_feedbacks
WHERE deleted_at IS NULL;
```

#### **APPROVE/REJECT (Manage Feedback):**

```sql
-- Approve feedback
UPDATE user_feedbacks 
SET is_approved = true, updated_at = CURRENT_TIMESTAMP
WHERE id = ? AND deleted_at IS NULL;

-- Reject/delete feedback (soft delete)
UPDATE user_feedbacks 
SET deleted_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP
WHERE id = ? AND deleted_at IS NULL;

-- Mark helpful
UPDATE user_feedbacks 
SET helpful_count = helpful_count + 1
WHERE id = ?;
```

#### **ANALYTICS:**

```sql
-- Rating distribution per product
SELECT p.name, uf.rating, COUNT(*) as count
FROM user_feedbacks uf
JOIN products p ON uf.product_id = p.id
WHERE uf.is_approved = true AND uf.deleted_at IS NULL
GROUP BY p.id, p.name, uf.rating
ORDER BY p.name, uf.rating;

-- Most reviewed products
SELECT p.name, COUNT(uf.id) as review_count, AVG(uf.rating) as avg_rating
FROM products p
LEFT JOIN user_feedbacks uf ON p.id = uf.product_id AND uf.is_approved = true
WHERE p.deleted_at IS NULL
GROUP BY p.id, p.name
ORDER BY review_count DESC
LIMIT 10;

-- Recent feedback
SELECT u.name, p.name, uf.rating, uf.comment, uf.created_at
FROM user_feedbacks uf
JOIN users u ON uf.user_id = u.id
JOIN products p ON uf.product_id = p.id
WHERE uf.is_approved = false AND uf.deleted_at IS NULL
ORDER BY uf.created_at DESC
LIMIT 20;
```

**Kolom untuk monitoring:**
- `rating` - Bintang 1-5
- `title` - Judul review
- `comment` - Isi feedback
- `is_verified_purchase` - Apakah pembeli asli
- `is_approved` - Status approval admin
- `helpful_count` - Berapa user anggap membantu
- `created_at` - Kapan dibuat
- `user_id` - Siapa yang menulis
- `product_id` - Produk mana yang direview

**Index:**
- `idx_feedbacks_is_approved` - List pending approval
- `idx_feedbacks_product_id` - Group by produk
- `idx_feedbacks_rating` - Filter by rating

---

## 8. Audit Trail untuk Admin Activities

**Tabel:** `admin_logs`

Setiap aktivitas admin tercatat otomatis:

```sql
-- Admin membuat produk
INSERT INTO admin_logs (
    admin_id, action, entity_type, entity_id, 
    new_values, ip_address, user_agent, description, created_at
) VALUES (
    ?, 'create', 'product', ?,
    jsonb_build_object('name', ?, 'sku', ?, 'price', ?),
    inet '192.168.1.1', 'Mozilla/5.0...',
    'Buat produk baru: Cetaphil Gentle Cleanser',
    CURRENT_TIMESTAMP
);

-- Admin edit harga produk
INSERT INTO admin_logs (
    admin_id, action, entity_type, entity_id,
    old_values, new_values, ip_address, user_agent, created_at
) VALUES (
    ?, 'update', 'product', ?,
    jsonb_build_object('price', 150000),
    jsonb_build_object('price', 125000),
    inet '192.168.1.1', 'Mozilla/5.0...',
    CURRENT_TIMESTAMP
);

-- Admin approve feedback
INSERT INTO admin_logs (
    admin_id, action, entity_type, entity_id,
    old_values, new_values, description, created_at
) VALUES (
    ?, 'update', 'feedback', ?,
    jsonb_build_object('is_approved', false),
    jsonb_build_object('is_approved', true),
    'Approve review dari user',
    CURRENT_TIMESTAMP
);

-- Query audit trail
SELECT * FROM admin_logs 
WHERE admin_id = ? 
  AND created_at >= NOW() - INTERVAL '7 days'
ORDER BY created_at DESC;

-- Tampilkan perubahan apa saja di sistem
SELECT a.created_at, u.name, a.action, a.entity_type, a.description, 
       a.old_values, a.new_values
FROM admin_logs a
JOIN users u ON a.admin_id = u.id
WHERE a.created_at >= NOW() - INTERVAL '30 days'
ORDER BY a.created_at DESC;
```

**Kolom yang dicatat:**
- `admin_id` - Admin mana yang aksi
- `action` - Jenis aksi (create, update, delete, approve, etc)
- `entity_type` - Apa yang diubah (product, article, feedback, user)
- `entity_id` - ID data yang diubah
- `old_values` - Nilai lama (JSONB)
- `new_values` - Nilai baru (JSONB)
- `ip_address` - IP address admin
- `user_agent` - Browser/client info
- `created_at` - Kapan aksi terjadi

---

## 9. Ringkasan Support Database untuk Admin

| Fitur Admin | Tabel Utama | Fitur Database | Query Kompleksitas |
|-----------|-----------|-----------|-----------|
| **1. Login & Logout** | `users` | Role check, is_active, last_login_at | ⭐ Sangat Mudah |
| **2. Kelola Profil** | `users`, `user_profiles` | UPDATE name, password | ⭐ Mudah |
| **3. CRUD Produk** | `products`, `brands`, `product_brands` | Full CRUD + M:N relations | ⭐⭐ Moderate |
| **4. CRUD Skin Guide** | `articles`, `article_tags` | Full CRUD + tags + publishing | ⭐⭐ Moderate |
| **5. Monitor Feedback** | `user_feedbacks` | Approval workflow + analytics | ⭐⭐⭐ Complex |
| **6. Audit Trail** | `admin_logs` | Automatic logging dengan JSONB | ⭐ Mudah |

---

## 10. Fitur Keamanan untuk Admin

```sql
-- 1. Role-based access (di aplikasi layer)
-- Hanya user dengan role = 'admin' bisa akses admin panel
WHERE role = 'admin' AND is_active = true

-- 2. Soft delete untuk safety
-- Data tidak pernah benar-benar hilang
UPDATE ... SET deleted_at = CURRENT_TIMESTAMP

-- 3. Audit trail lengkap
-- Setiap perubahan tercatat di admin_logs dengan old_values & new_values

-- 4. Constraints untuk data integrity
CHECK (price > 0)                    -- Harga valid
CHECK (stock_quantity >= 0)          -- Stok non-negative
CHECK (rating >= 1 AND rating <= 5)  -- Rating 1-5
CHECK (confidence_score >= 0 AND confidence_score <= 1)

-- 5. IP & User Agent tracking
-- Bisa deteksi akses mencurigakan

-- 6. Timestamps untuk tracing
-- created_at, updated_at, deleted_at, last_login_at
```

---

**Status:** ✅ Siap untuk implementasi di Supabase PostgreSQL
**Next Step:** Buat Models & Migrations di Laravel
