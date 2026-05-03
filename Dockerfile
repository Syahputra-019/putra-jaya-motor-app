FROM php:8.2-apache

# 1. Install library dasar + curl
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    sqlite3 \
    libsqlite3-dev

# 2. Install Node.js & NPM (Buat nge-build Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 3. Bersihkan cache instalasi
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 4. Install ekstensi PHP
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# 5. Aktifkan mod_rewrite Apache (Wajib buat Laravel)
RUN a2enmod rewrite

# 6. Ubah settingan server agar membaca folder /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 7. Set tempat kerja di dalam server
WORKDIR /var/www/html

# 8. Copy semua file project kamu ke server
COPY . /var/www/html

# 9. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# 10. Install NPM & Build Vite Assets (INI SOLUSI ERRORNYA)
RUN npm install
RUN npm run build

# 11. Bikin folder database, bikin file sqlite kosong, & atur izin
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
RUN touch /var/www/html/database/database.sqlite
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# 12. JALAN TIKUS: Migrasi otomatis sebelum server Apache nyala
CMD php artisan migrate --force && apache2-foreground