FROM php:8.2-apache

# 1. Install library yang dibutuhkan sistem (tambah git & curl)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# 2. Bersihkan cache instalasi
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Install ekstensi PHP untuk Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 4. Aktifkan mod_rewrite Apache (Wajib buat Laravel)
RUN a2enmod rewrite

# 5. Ubah settingan server agar membaca folder /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 6. Set tempat kerja di dalam server
WORKDIR /var/www/html

# 7. Copy semua file project kamu ke server
COPY . /var/www/html

# 8. Install Composer (Pakai jurus --ignore-platform-reqs biar gak rewel)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# 9. Bikin folder & atur izin file
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache