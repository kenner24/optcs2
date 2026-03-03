#!/bin/bash
set -e

echo "=== OPTCS Backend Startup (Railway) ==="

# Railway provides $PORT - use it for nginx, default to 8000 for local
export NGINX_PORT=${PORT:-8000}
echo "Listening on port: $NGINX_PORT"

# Substitute the port into nginx config
envsubst '${NGINX_PORT}' < /etc/nginx/nginx-backend.conf.template > /etc/nginx/sites-available/default

# Create .env file from environment variables (Laravel requires this file)
echo "Creating .env file from environment variables..."
env | grep -E '^(APP_|DB_|MAIL_|CACHE_|SESSION_|QUEUE_|LOG_|FRONT_END|SANCTUM_|BROADCAST_|FILESYSTEM_|REDIS_|AWS_|PUSHER_|MIX_|VITE_|QUICKBOOKS_|SALESFORCE_|GOOGLE_)' > /var/www/html/.env 2>/dev/null || true

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Seed database (only if needed)
echo "Checking if seeding is needed..."
php artisan db:seed --force 2>/dev/null || echo "Seeding skipped (already seeded)"

# Clear and cache config
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
php artisan storage:link 2>/dev/null || true

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "=== Starting services ==="
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
