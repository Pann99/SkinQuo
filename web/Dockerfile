# ==============================================================================
# STAGE 1: Build Frontend Assets (Node.js & Vite)
# ==============================================================================
FROM node:20-alpine AS frontend-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# ==============================================================================
# STAGE 2: Runtime Application (PHP 8.3 & Apache)
# ==============================================================================
FROM php:8.3-apache

# 1. Install dependencies dasar (Sangat bersih tanpa paket tambahan yang memicu konflik)
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip gd bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. FIX TOTAL & AGRESIF: Hancurkan fisik semua file MPM lain, paksa hanya prefork yang hidup
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf || true \
    && cp /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/ || true \
    && (cp /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/ || true) \
    && a2enmod rewrite

# 3. Arahkan Document Root Apache langsung ke folder /public milik Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Tentukan direktori kerja
WORKDIR /var/www/html

# 5. Salin file proyek dan hasil kompilasi desain Vite dari Stage 1
COPY . .
COPY --from=frontend-builder /app/public/build ./public/build

# 6. Install Composer untuk package PHP
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader

# 7. Atur permission folder storage & cache Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Buka port standar Railway
EXPOSE 80

# 9. Proteksi Terakhir: Hapus sisa file MPM ilegal tepat 1 detik sebelum Apache dinyalakan
CMD rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.* || true \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && apache2-foreground
