FROM php:8.2-apache

# Install library yang dibutuhkan sistem
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install ekstensi PHP untuk Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Aktifkan mod_rewrite Apache (wajib buat routing Laravel)
RUN a2enmod rewrite

# Ubah settingan server agar membaca folder /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy semua file project kamu ke dalam server
COPY . /var/www/html

# Install Composer dan dependensi Laravel
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Atur izin folder biar nggak error 500 (Permission Denied)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache