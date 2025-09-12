# Use PHP 8.4 with FPM for Laravel 12
FROM php:8.4-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    redis-tools \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required for Laravel
RUN docker-php-ext-install \
    pdo \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js for asset compilation
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Copy composer files first for better caching
COPY composer.json ./
COPY composer.lock* ./

# Install PHP dependencies (including dev for Laravel Pail)
RUN composer install --optimize-autoloader --no-scripts

# Copy package.json files for Node.js
COPY package*.json ./

# Copy application files
COPY . .

# Install Node.js dependencies (including dev dependencies for development)
RUN npm install

# Run composer scripts now that all files are present
RUN composer dump-autoload --optimize

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Create SQLite database if it doesn't exist
RUN mkdir -p /var/www/html/database && touch /var/www/html/database/database.sqlite

# Copy environment file - prefer .env.docker if available, fallback to .env.example
COPY .env.docker* .env.example ./
RUN if [ -f ".env.docker" ]; then cp .env.docker .env; else cp .env.example .env; fi

# Generate application key
RUN php artisan key:generate

# Set proper environment for Docker
RUN sed -i 's/DB_DATABASE=.*/DB_DATABASE=\/var\/www\/html\/database\/database.sqlite/' .env && \
    sed -i 's/REDIS_HOST=.*/REDIS_HOST=redis/' .env && \
    sed -i 's/CACHE_STORE=.*/CACHE_STORE=redis/' .env && \
    sed -i 's/SESSION_DRIVER=.*/SESSION_DRIVER=redis/' .env && \
    sed -i 's/QUEUE_CONNECTION=.*/QUEUE_CONNECTION=redis/' .env

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Use entrypoint script
ENTRYPOINT ["docker-entrypoint.sh"]
