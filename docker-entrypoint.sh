#!/bin/bash
set -e

# Generate application key if not set or is empty
if ! grep -q "APP_KEY=base64:" .env || grep -q "APP_KEY=$" .env; then
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

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Clear and cache config
php artisan config:cache
php artisan route:cache

# Start PHP-FPM
echo "Starting PHP-FPM..."
exec php-fpm
