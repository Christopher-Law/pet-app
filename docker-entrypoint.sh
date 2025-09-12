#!/bin/bash
set -e

# Generate application key if not set or corrupted in .env file
EXISTING_KEY=$(grep "^APP_KEY=" .env 2>/dev/null | cut -d'=' -f2- || echo "")
if [[ -z "$EXISTING_KEY" ]] || [[ ! "$EXISTING_KEY" =~ ^base64:[A-Za-z0-9+/]{44}=?$ ]]; then
    echo "Generating application key..."
    # Remove any existing APP_KEY line and add clean one
    grep -v "^APP_KEY=" .env > .env.tmp 2>/dev/null || cp .env .env.tmp
    echo "" >> .env.tmp
    echo "APP_KEY=" >> .env.tmp
    mv .env.tmp .env
    php artisan key:generate --force
fi

# Wait for Redis to be ready
echo "Waiting for Redis..."
until redis-cli -h redis ping; do
    echo "Redis is unavailable - sleeping"
    sleep 1
done
echo "Redis is ready"

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Clear and cache config
php artisan config:cache
php artisan route:cache

# Only cache views in production
if [ "$APP_ENV" = "production" ]; then
    echo "Caching views for production..."
    php artisan view:cache
fi

# Start PHP-FPM
echo "Starting PHP-FPM..."
exec php-fpm