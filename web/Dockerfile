# ==============================================================================
# STAGE 1: Build Frontend Assets (Node.js & Vite)
# Di sinilah proses 'npm run build' dijalankan untuk desain dan CSS Anda
# ==============================================================================
FROM node:20-alpine AS frontend-builder
WORKDIR /app

# Copy file konfigurasi npm
COPY package*.json ./
RUN npm install

# Copy seluruh file proyek ke dalam builder frontend
COPY . .

# Eksekusi Vite untuk compile CSS/JS/Tailwind
RUN npm run build


# ==============================================================================
# STAGE 2: Runtime Application (PHP & Apache)
# Menyiapkan server Laravel dan menghubungkannya dengan hasil build dari Stage 1
# ==============================================================================
FROM php:8.3-apache

# 1. Install system dependencies, PostgreSQL Client, & ekstensi PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    postgresql-client \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip gd bcmath

# 2. Aktifkan mod_rewrite Apache agar routing Laravel (URL) berfungsi normal
RUN a2enmod rewrite

# 3. Arahkan Document Root Apache langsung ke folder /public milik Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Tentukan direktori kerja di dalam container server
WORKDIR /var/www/html

# 5. Salin seluruh file proyek Laravel ke dalam direktori kerja server
COPY . .

# 6. Salin hasil compile Vite (desain) dari STAGE 1 ke dalam folder /public
COPY --from=frontend-builder /app/public/build ./public/build

# 7. Install Composer untuk mengelola package PHP
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# 8. Berikan izin akses folder agar Laravel bisa menulis log, cache, dan session
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Buka port 80 agar bisa diakses oleh Railway
EXPOSE 80

# 10. Optimasi Laravel, jalankan migrasi Supabase, dan nyalakan server Apache
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    apache2-foreground
