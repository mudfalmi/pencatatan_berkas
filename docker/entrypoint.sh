#!/bin/sh
set -eu

# Railway injects PORT (usually 8080). The PHP built-in server defaults to 8080.
PORT="${PORT:-8080}"

case "$PORT" in
    ''|*[!0-9]*)
        echo "Invalid PORT value: $PORT" >&2
        exit 1
        ;;
esac

# Keep Laravel's writable directories available even when a fresh volume is
# mounted/attached to the container.
mkdir -p \
    /var/www/html/storage/app/public \
    /var/www/html/storage/framework/cache/data \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs \
    /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure public/storage -> storage/app/public exists on every boot.
# This is what makes uploaded files and storage-backed logos publicly
# accessible when Railway starts a fresh container.
cd /var/www/html
php artisan storage:link --force >/dev/null 2>&1 || true

exec "$@"
