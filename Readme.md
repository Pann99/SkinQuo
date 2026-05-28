# 🚀 SkinQuo - Smart Skin Analysis Platform

SkinQuo adalah platform e-learning dan konsultasi skincare berbasis AI yang membantu pengguna menemukan rekomendasi produk dan rutinitas skincare sesuai tipe serta kondisi kulit mereka.

---

# 👥 Anggota Kelompok 1

| No | Nama                       | NIM        | Role                 |
| -- | -------------------------- | ---------- | -------------------- |
| 1  | Axelo Matthew Terang Barus | 2341760001 | Knowledge Engineer   |
| 2  | Khoir Karol Nurzuraidah    | 2341760048 | Documentation Expert |
| 3  | Lyra Faiqah Bilqis         | 2341760013 | Backend Developer    |
| 4  | Nadya Hapsari Putri        | 2341760179 | Frontend Developer   |
| 5  | Pandya Cahya               | 2341760053 | UI/UX Designer       |

---

# 🎯 Deskripsi Proyek

SkinQuo merupakan platform edukasi dan konsultasi skincare yang dirancang untuk membantu pengguna memahami kondisi kulit mereka melalui pendekatan AI dan Natural Language Processing (NLP).

Platform ini menyediakan:

* Konsultasi kulit berbasis AI
* Rekomendasi produk personal
* Edukasi skincare
* Dashboard admin management
* Sistem feedback pengguna

---

# ✨ Fitur Utama

## 👤 User Features

### 🏠 Home Page

* Hero section interaktif
* Featured skincare products
* Featured articles
* Testimonials
* Newsletter integration

### 🛍️ Product Catalog

* Product filtering & searching
* Product detail page
* Ingredients information
* User reviews & ratings
* Responsive product grid

### 📚 Skin Guide

* Artikel edukasi skincare
* Kategori artikel
* Tips & tricks skincare
* Ingredients education
* Related article recommendations

### 🔍 Smart Consultation

* Multi-step consultation form
* Skin concern analysis
* Personalized skincare recommendation
* AI-powered recommendation engine
* Consultation result summary

### 💬 Feedback System

* User feedback form
* Community feedback display
* Rating system
* Admin moderation

### 👤 User Profile

* Consultation history
* Saved preferences
* Download consultation report
* Account settings

---

## 🛠️ Admin Features

### 📊 Admin Dashboard

* User statistics
* Product statistics
* Feedback monitoring
* Quick management actions

### 🏪 Product Management

* Add/Edit/Delete product
* Product image upload
* Product categorization
* Product stock management

### ✍️ Article Management

* Add/Edit/Delete article
* Article categorization
* Cover image upload
* Draft & publish system

### 📋 Feedback Monitoring

* View user feedback
* Approve/Reject feedback
* Feedback filtering
* User interaction tracking

---

# 🛠️ Tech Stack

## Backend (Laravel)

* Laravel 12
* PHP 8.2+
* PostgreSQL
* REST API
* Laravel Sanctum

## AI Recommendation Engine

* Python 3.10.11
* FastAPI
* Uvicorn
* sentence-transformers
* scikit-learn
* rapidfuzz
* Sastrawi
* pandas

## Frontend

* Laravel Blade
* Tailwind CSS v4
* Vanilla JavaScript
* Alpine.js

## Tools & DevOps

* Git & GitHub
* Composer
* npm
* pip
* PHPUnit

---

# 🚀 Quick Start

# 1️⃣ Setup Laravel Backend

## 📦 Prerequisites

Pastikan telah menginstall:

* PHP 8.2+
* Composer
* Node.js & npm
* PostgreSQL

---

## ⚙️ Installation

```bash
# Clone repository
git clone <repository-url>

# Masuk ke project
cd SkinQuo

# Install dependency PHP
composer install

# Install dependency frontend
npm install

# Copy environment
cp .env.example .env

# Generate app key
php artisan key:generate
```

---

## 🗄️ Database Setup

Konfigurasi database pada file `.env`

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=skinquo
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

Lalu jalankan migration:

```bash
php artisan migrate
```

---

## ▶️ Run Laravel Server

```bash
php artisan serve
```

Laravel akan berjalan pada:

```bash
http://127.0.0.1:8000
```

---

# 2️⃣ Setup AI Service Engine (Python)

AI Engine digunakan untuk melakukan:

* Analisis konsultasi kulit
* NLP preprocessing
* Semantic similarity matching
* Personalized recommendation

---

## 📦 Prerequisites

Pastikan Python versi berikut telah terinstall:

```bash
Python 3.10.11
```

Cek versi Python:

```bash
python --version
```

---

## ⚙️ AI Service Installation

Buka terminal baru lalu arahkan ke folder AI Service yang berisi:

```bash
main.py
requirements.txt
```

---

### 1. Buat Virtual Environment

```bash
python -m venv venv
```

---

### 2. Aktivasi Virtual Environment

#### Windows (CMD)

```bash
venv\Scripts\activate
```

#### Windows (PowerShell)

```bash
.\venv\Scripts\Activate.ps1
```

#### Linux / macOS

```bash
source venv/bin/activate
```

Jika berhasil aktif akan muncul indikator:

```bash
(venv)
```

---

### 3. Install Dependencies

```bash
pip install -r requirements.txt
```

Dependency utama:

* FastAPI
* Uvicorn
* sentence-transformers
* scikit-learn
* rapidfuzz
* pandas
* Sastrawi

---

## ▶️ Menjalankan AI Service

```bash
python -m uvicorn main:app --host 127.0.0.1 --port 8001 --reload
```

---

## 🌐 API Endpoint

AI Service akan berjalan pada:

```bash
http://127.0.0.1:8001
```

Swagger API Documentation:

```bash
http://127.0.0.1:8001/docs
```

ReDoc Documentation:

```bash
http://127.0.0.1:8001/redoc
```

---

# 🔗 System Architecture

```text
Laravel Frontend
        ↓
REST API Request
        ↓
FastAPI AI Service
        ↓
NLP & Recommendation Engine
        ↓
Recommendation Response
        ↓
Consultation Result Page
```

---

# 🧠 AI Capabilities

SkinQuo AI mendukung:

* Skin concern detection
* Semantic recommendation
* Typo correction
* Indonesian NLP preprocessing
* Personalized product recommendation
* Content-based filtering

---

# 📁 Project Structure

```text
SkinQuo/
├── app/
├── public/
├── resources/
├── routes/
├── database/
├── storage/
├── main.py
├── requirements.txt
├── composer.json
└── README.md
```

---

# 🚨 Troubleshooting

## Module Not Found

Pastikan virtual environment aktif sebelum menjalankan AI Service.

---

## Port Already In Use

Ganti port:

```bash
python -m uvicorn main:app --host 127.0.0.1 --port 8002 --reload
```

---

## PowerShell Execution Policy Error

Jalankan PowerShell sebagai Administrator:

```powershell
Set-ExecutionPolicy RemoteSigned
```

---

# 📌 Notes

* Laravel Backend dan AI Service harus berjalan bersamaan.
* Pastikan koneksi database PostgreSQL aktif.
* Gunakan environment terpisah untuk development dan production.

---

# 📄 License

Project ini dibuat untuk keperluan pembelajaran dan Project Based Learning (PBL).
