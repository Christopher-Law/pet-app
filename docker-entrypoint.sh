#!/bin/bash
set -e

# Generate application key if not set in .env file
if ! grep -q "^APP_KEY=base64:" .env 2>/dev/null; then
    echo "Generating application key..."
    # Add APP_KEY line if it doesn't exist
    if ! grep -q "^APP_KEY=" .env 2>/dev/null; then
        echo "" >> .env
        echo "APP_KEY=" >> .env
    fi
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