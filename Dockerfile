# syntax=docker/dockerfile:1

# ---------------------------------------------------------------------------
# 1. PHP base: extensions + Composer dependencies
# ---------------------------------------------------------------------------
FROM php:8.2-cli AS phpbase

# System libraries required to build the PHP extensions.
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        libicu-dev \
        libpq-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        intl \
        opcache \
        pdo_pgsql \
        pgsql \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer is only needed while building the image.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install dependencies first so Docker can cache this layer.
COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-interaction \
        --no-progress \
        --no-scripts \
        --no-autoloader \
        --prefer-dist

# ---------------------------------------------------------------------------
# 2. Build frontend assets (Vite -> public/build)
# ---------------------------------------------------------------------------
FROM node:22-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY vite.config.js ./
COPY resources ./resources

# resources/css/theme.css imports public/assets/css/theme.css, so the
# public directory must also exist during the Vite build.
COPY public ./public

# Laravel pagination views are scanned by Tailwind via @source in
# resources/css/app.css, so make the installed vendor directory available.
COPY --from=phpbase /var/www/html/vendor ./vendor

RUN npm run build

# ---------------------------------------------------------------------------
# 3. Final runtime image (php:8.2-cli + PHP built-in server)
# ---------------------------------------------------------------------------
FROM phpbase AS final

COPY . .

# Replace the assets from the repository with a fresh Vite build.
COPY --from=assets /app/public/build ./public/build

# Normalize the entrypoint line endings (important when building on Windows)
# and make sure Laravel's writable directories and storage link exist.
COPY docker/entrypoint.sh /usr/local/bin/app-entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/app-entrypoint.sh \
    && chmod +x /usr/local/bin/app-entrypoint.sh \
    && composer dump-autoload --no-dev --no-scripts --optimize --no-interaction \
    && mkdir -p \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && php artisan package:discover --ansi \
    && (php artisan storage:link --force || true) \
    && chown -R www-data:www-data storage bootstrap/cache

# Production defaults. Railway service variables override these.
ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    PORT=8080 \
    PHP_CLI_SERVER_WORKERS=4

EXPOSE 8080

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD php -r '$port = (int) (getenv("PORT") ?: 8080); $s = @fsockopen("127.0.0.1", $port); exit($s ? 0 : 1);'

ENTRYPOINT ["/usr/local/bin/app-entrypoint.sh"]

# IMPORTANT: do not pass public/index.php as the router script here.
# That makes PHP route static files (CSS/JS/logo) through Laravel, which
# returns redirects/404s. Serving the public/ directory directly lets the
# built-in server return static assets as-is.
CMD ["sh", "-c", "exec php -S 0.0.0.0:${PORT:-8080} -t public"]
