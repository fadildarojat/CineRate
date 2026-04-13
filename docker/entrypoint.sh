#!/bin/sh

echo "=========================================="
echo "  CineRate - Starting deployment..."
echo "=========================================="

# Railway provides PORT env variable - update nginx to use it
PORT=${PORT:-8080}
echo "[1/6] Configuring Nginx on port: $PORT"
sed -i "s/listen 8080/listen $PORT/" /etc/nginx/http.d/default.conf

# Create .env file if it doesn't exist (Railway sets env vars directly)
if [ ! -f /var/www/html/.env ]; then
    echo "[2/6] Creating .env file..."
    touch /var/www/html/.env
fi

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "[3/6] Generating application key..."
    php artisan key:generate --force 2>&1 || echo "  -> Warning: key:generate failed (set APP_KEY env var in Railway)"
else
    echo "[3/6] APP_KEY already set, skipping key generation"
fi

# Cache configuration for performance
echo "[4/6] Caching configuration..."
php artisan config:cache 2>&1 || echo "  -> Warning: config:cache failed"
php artisan route:cache 2>&1 || echo "  -> Warning: route:cache failed"
php artisan view:cache 2>&1 || echo "  -> Warning: view:cache failed"

# Run database migrations (non-fatal if DB not configured yet)
echo "[5/6] Running database migrations..."
php artisan migrate --force 2>&1 || echo "  -> Warning: migrations failed (check DB env vars)"

# Create storage symlink
echo "[6/6] Creating storage link..."
php artisan storage:link --force 2>/dev/null || true

echo "=========================================="
echo "  CineRate is ready on port $PORT"
echo "=========================================="

# Start Supervisor (runs Nginx + PHP-FPM)
exec /usr/bin/supervisord -c /etc/supervisord.conf
