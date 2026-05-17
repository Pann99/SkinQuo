# SkinQuo - Smart Skin Analysis Platform

Ini adalah proyek PBL SkinQuo - platform e-learning dan konsultasi skincare yang inovatif untuk membantu pengguna menemukan rutinitas skincare yang tepat berdasarkan tipe kulit mereka.

## 👥 Anggota Kelompok 1

| No. | Nama | NIM | Tugas |
|-----|------|-----|-------|
| 1 | Axelo Matthew Terang Barus | 2341760001 | Knowledge Engineer |
| 2 | Khoir Karol Nurzuraidah | 2341760048 | Documentation Expert |
| 3 | Lyra Faiqah Bilqis | 2341760013 | BackEnd Developer |
| 4 | Nadya Hapsari Putri | 2341760179 | Frontend Developer |
| 5 | Pandya Cahya | 2341760053 | UI/UX Designer |

## 🎯 Deskripsi Proyek

**SkinQuo** adalah platform edukasi dan konsultasi skincare yang dirancang untuk membantu pengguna (terutama wanita Indonesia) dalam menemukan rutinitas perawatan kulit yang optimal sesuai dengan tipe dan kondisi kulit mereka. Platform ini mengintegrasikan:

- **Konsultasi Interaktif**: Sistem analisis kulit berbasis AI yang memberikan rekomendasi produk personal
- **Panduan Edukasi**: Koleksi artikel lengkap tentang skincare, ingredients, dan routine tips
- **Katalog Produk**: Database produk skincare dengan filtering berdasarkan jenis kulit dan concern
- **Dashboard Admin**: Interface untuk mengelola produk, artikel, dan feedback pengguna
- **Feedback**: Sistem feedback untuk meningkatkan kualitas layanan

---

## 🎯 Fitur Utama

### **User Features**

#### 1. **Home Page (Halaman Utama)**
- Hero banner dengan call-to-action
- Featured articles & products showcase
- Testimonial section dari user
- Newsletter signup integration
- SEO-optimized landing page

#### 2. **Catalog (Katalog Produk)** 🛍️
- Grid produk interaktif dengan filter (kategori, harga, rating)
- Search & sort functionality (A-Z, harga, rating)
- Detail product page dengan informasi lengkap:
  - Ingredients list dengan deskripsi
  - How-to-use instructions
  - User reviews & ratings
  - Related products suggestions
  - Stock availability status
- Responsive grid design untuk semua ukuran device

#### 3. **Skin Guide (Panduan Perawatan Kulit)** 📚
- Artikel edukatif tentang skincare dari expert
- Grid artikel dengan kategori filter:
  - Basic Care Routine
  - Advanced Routines
  - Ingredients Education
  - Tips & Tricks
- Detail article page dengan:
  - Full article content
  - Author & publication date
  - Reading time estimate
  - Recommended related articles
  - Comment section ready
- Tag-based article discovery

#### 4. **Smart Consultation (Konsultasi Kulit)** 🔍
- Form interaktif 6-langkah untuk analisis kulit:
  1. Skin Story (deskripsi kondisi kulit)
  2. Skin Type Detection (pilihan jenis kulit)
  3. Main Concerns (masalah utama kulit)
  4. Preferences (preferensi ingredients)
  5. Confirmation modal dengan detected traits
  6. Result dengan rekomendasi lengkap
- Result page yang menampilkan:
  - Skin health score & metrics
  - Personalized product recommendations
  - Suggested skincare routine
  - Detailed trait analysis
  - Saved consultation history (untuk registered users)
- No login redirect - guest users dapat melihat hasil

#### 5. **Feedback System** 💬
- Form feedback dari semua pengguna
- Display feedback list untuk community insights
- Admin moderation capabilities
- Rating & sentiment tracking

#### 6. **User Profile** (Authenticated Users) 👤
- Profile information management
- Consultation history tracking
- Saved preferences & wishlist
- Download consultation reports
- Account settings & preferences

---

### **Admin Features**

#### 7. **Admin Dashboard** 📊
- Premium dashboard dengan statistics cards:
  - Total users, products, articles, pending feedback
  - Quick action links untuk management
  - Admin profile information
  - System health status
- Real-time metrics & analytics
- Quick shortcuts untuk common tasks

#### 8. **Product Management** 🏪
- **Index**: Tabel semua produk dengan filter & search
  - Columns: Product name, brand, price, stock, status
  - Action buttons: View, Edit, Delete
  - Pagination & bulk operations ready
  
- **Create**: Form tambah produk baru dengan:
  - Product details (name, brand, category)
  - Pricing & stock information
  - Image upload dengan drag-drop UI
  - SEO metadata (slug, description)
  - Publishing status (draft/published)
  - CSRF protection & validation
  
- **Edit**: Form edit dengan:
  - Pre-filled data dari database
  - Current image preview
  - Optional image replacement
  - Timestamp tracking (created_at, updated_at)

#### 9. **Article Management** ✍️
- **Index**: Tabel artikel dengan filtering
  - Search by title/category
  - Status filter (published/draft)
  - Author & date tracking
  
- **Create**: Form editor artikel dengan:
  - Title, category, tags input
  - Rich content editor (textarea)
  - Cover image upload
  - Publish/draft status selector
  - SEO slug generation
  
- **Edit**: Article editor dengan:
  - Pre-filled content
  - Current cover preview
  - Optional image update
  - Version history ready

#### 10. **Feedback Monitor** 📋
- Dashboard untuk melihat user feedback
- Feedback cards dengan:
  - User info & feedback type
  - Message content
  - Status tracking (pending/approved/rejected)
  - Helpful/helpful action tracking
- Filter by status, type, user
- Search functionality
- Admin action buttons: Approve, Reject, Mark as Helpful

---

## 🛠 Tech Stack

**Backend**
- **Framework**: Laravel 12.56.0 (Modern PHP framework)
- **Language**: PHP 8.2+
- **Database**: PostgreSQL 15+ via Supabase (Cloud-hosted)
- **Authentication**: Laravel Sanctum & custom middleware
- **API**: RESTful API dengan JSON responses

**Frontend**
- **Templating**: Laravel Blade Engine
- **Styling**: Tailwind CSS v4 (via CDN)
- **JavaScript**: Vanilla JS + Alpine.js (optional interactivity)
- **Icons**: Unicode emoji + custom SVG icons
- **Design System**: Custom color palette (cream, peach, brown)

**DevOps & Tools**
- **Package Manager**: Composer (PHP), npm (Node.js)
- **Version Control**: Git + GitHub
- **Development Server**: Laravel Artisan serve
- **Testing**: PHPUnit (ready for implementation)
- **Documentation**: Markdown files

**Design & UX**
- **Typography**: Playfair Display (headings), Poppins (body)
- **Color Scheme**: 
  - Cream (#FFEAC5) - Primary background
  - Peach (#FFDBB5) - Accent & hover states
  - Brown (#6C4E31) - Secondary text
  - Dark Brown (#603F26) - Headings & buttons
- **Responsive**: Mobile-first design, breakpoints at 640px, 820px, 1024px
- **Accessibility**: WCAG compliant, semantic HTML, proper ARIA labels

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm

### Installation

```bash
# Clone repository
git clone <repo-url>
cd SkinQuo

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations (optional, app works without DB)
php artisan migrate

# Start development server
php artisan serve
# Server will be available at http://localhost:8000
```
