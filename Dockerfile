FROM php:8.2-apache

# GD (для превьюшек фото) + PDO MySQL (для базы)
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev \
        libjpeg-dev \
        libwebp-dev \
        libfreetype6-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
    && docker-php-ext-install -j"$(nproc)" gd pdo_mysql \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Апач по умолчанию отдаёт /var/www/html — это и есть public_html проекта (смонтируется томом)
