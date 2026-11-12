FROM php:8.2-cli

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip bcmath opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js (untuk build asset Vite/npm)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Install PHP dependencies & Node dependencies, then build assets
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && npm install \
    && npm run build

# Clear cache, link storage, and set permissions
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear \
    && php artisan storage:link \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Point directly to Laravel's public directory
WORKDIR /var/www/html/public

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "index.php"]