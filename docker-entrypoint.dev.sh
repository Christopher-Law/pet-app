#!/bin/bash
set -e

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
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

# Clear and cache config (but don't cache views in dev)
php artisan config:cache
php artisan route:cache

# Start PHP-FPM
echo "Starting PHP-FPM..."
exec php-fpm
