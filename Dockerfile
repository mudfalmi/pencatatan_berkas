FROM php:8.2-cli

# Install system dependencies & PHP extensions yang dibutuhkan Laravel & PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip bcmath opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js (untuk build asset Vite/npm)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Salin seluruh file proyek
COPY . /var/www/html

# Install dependensi PHP untuk production (tanpa dev package agar ringan)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Berikan hak akses folder storage dan bootstrap/cache agar Laravel bisa menulis log & session
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Arahkan root folder server langsung ke direktori public Laravel
WORKDIR /var/www/html/public

EXPOSE 8080

# Jalankan PHP built-in server dengan port Railway
CMD ["php", "-S", "0.0.0.0:8080", "index.php"]