#!/bin/sh

echo "=========================================="
echo "  CineRate - Starting..."
echo "=========================================="

PORT=${PORT:-8080}

# Create .env if missing
if [ ! -f /var/www/html/.env ]; then
    echo "[1/4] Creating .env file..."
    touch /var/www/html/.env
fi

# App key
if [ -z "$APP_KEY" ]; then
    echo "[2/4] Generating APP_KEY..."
    php artisan key:generate --force 2>&1 || echo "  -> key:generate skipped"
else
    echo "[2/4] APP_KEY is set"
fi

# Cache & optimize
echo "[3/4] Optimizing..."
php artisan config:clear 2>&1 || true
php artisan route:clear 2>&1 || true
php artisan view:clear 2>&1 || true

# Migrations
echo "[4/4] Running migrations..."
php artisan migrate --force 2>&1 || echo "  -> migrations skipped (check DB config)"

# Storage link
php artisan storage:link --force 2>/dev/null || true

echo "=========================================="
echo "  CineRate ready on port $PORT"
echo "=========================================="

# Start Laravel server directly
exec php artisan serve --host=0.0.0.0 --port=$PORT
