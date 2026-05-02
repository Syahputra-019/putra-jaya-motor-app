FROM dunglas/frankenphp

# Install ekstensi PHP yang dibutuhkan Laravel
RUN install-php-extensions \
    pdo_mysql \
    pdo_pgsql \
    gd \
    intl \
    zip \
    opcache

# Copy semua file project kamu ke dalam server
COPY . /app

# Atur agar server membaca folder public
ENV SERVER_NAME=":80"