#!/bin/sh
set -e

echo "🚀 Starting CineRate deployment..."

# Railway provides PORT env variable - update nginx to use it
PORT=${PORT:-8080}
echo "📡 Configuring Nginx on port: $PORT"
sed -i "s/listen 8080/listen $PORT/" /etc/nginx/http.d/default.conf

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Cache configuration for performance
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Create storage symlink
echo "🔗 Creating storage link..."
php artisan storage:link --force 2>/dev/null || true

echo "✅ CineRate is ready! Listening on port $PORT"

# Start Supervisor (runs Nginx + PHP-FPM)
exec /usr/bin/supervisord -c /etc/supervisord.conf
