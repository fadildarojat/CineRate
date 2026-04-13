#!/bin/sh

echo "=========================================="
echo "  CineRate - Starting..."
echo "=========================================="

PORT=${PORT:-8080}
echo "PORT = $PORT"
echo "DB_CONNECTION = $DB_CONNECTION"
echo "APP_KEY = ${APP_KEY:-(not set)}"

# Create .env if missing
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env..."
    touch /var/www/html/.env
fi

# App key
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force 2>&1 || true
fi

# Clear leftover cache
php artisan config:clear 2>&1 || true
php artisan route:clear 2>&1 || true  
php artisan view:clear 2>&1 || true

# Migrations (skip if fails)
echo "Running migrations..."
php artisan migrate --force 2>&1 || echo "Migrations skipped"

# Storage link
php artisan storage:link --force 2>/dev/null || true

echo "=========================================="
echo "  Starting PHP server on 0.0.0.0:$PORT"  
echo "=========================================="

# Use PHP built-in server directly (most reliable)
exec php -S 0.0.0.0:$PORT -t /var/www/html/public
