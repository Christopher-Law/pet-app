# Pet App

A Laravel-based pet management application with Docker support.

## Demo Notes

> **⚠️ Important:** This is a demo application with no user authentication or ownership system. In a real-world application, each pet would belong to a specific user (with a `user_id` foreign key), but for demonstration purposes, all pets are stored without ownership associations.

## Prerequisites

- Docker & Docker Compose
- Git

## Quick Start

1. **Clone and setup:**
```bash
git clone <repository-url>
cd pet-app
touch database/database.sqlite
```

2. **Build and start containers:**
```bash
docker-compose up -d --build
```

3. **Access the application:**
   - **Application**: http://localhost:8000
   - **Redis**: localhost:6379

## Architecture

- **NGINX**: Web server and reverse proxy
- **PHP-FPM**: Laravel application runtime
- **Redis**: Cache, sessions, and queues
- **SQLite**: Database (file-based)

## Development Commands

### Container Management
```bash
# Start all services
docker-compose up -d

# Stop all services
docker-compose down

# Restart services
docker-compose restart

# Rebuild and restart
docker-compose down && docker-compose up -d --build

# View logs
docker-compose logs -f app    # PHP-FPM logs
docker-compose logs -f nginx  # NGINX logs
docker-compose logs -f redis  # Redis logs
```

### Application Commands
```bash
# Access PHP container
docker-compose exec app bash

# Laravel Artisan commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan migrate:fresh
docker-compose exec app php artisan tinker
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:list
docker-compose exec app php artisan queue:work

# Composer commands
docker-compose exec app composer install
docker-compose exec app composer update
docker-compose exec app composer dump-autoload

# Asset management
docker-compose exec app npm install     # Install dependencies
docker-compose exec app npm run build   # Build for production
docker-compose exec app npm run dev     # Build for development
```

### Database Commands
```bash
# Run migrations
docker-compose exec app php artisan migrate

# Reset database
docker-compose exec app php artisan migrate:fresh

# Seed database
docker-compose exec app php artisan db:seed

# Fresh install with seeding
docker-compose exec app php artisan migrate:fresh --seed
```

## Troubleshooting

### Common Issues

**502 Bad Gateway:**
- Check if PHP-FPM is running: `docker-compose logs app`
- Restart containers: `docker-compose restart`

**Permission Issues:**
```bash
# Fix Laravel permissions
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 755 storage bootstrap/cache
```

**Clear All Caches:**
```bash
docker-compose exec app php artisan optimize:clear
```

**Container Won't Start:**
```bash
# Check logs for errors
docker-compose logs app
docker-compose logs nginx
docker-compose logs redis

# Rebuild containers
docker-compose down
docker-compose up -d --build
```

## Project Structure

```
pet-app/
├── docker/
│   └── nginx/
│       └── nginx.conf          # NGINX configuration
├── docker-compose.yml          # Service definitions
├── Dockerfile                  # PHP-FPM container
├── docker-entrypoint.sh       # Container startup script
├── .env.docker                # Docker environment variables
├── .dockerignore               # Files excluded from build
└── database/
    └── database.sqlite         # SQLite database file
```

## Environment Configuration

The application uses different environment files:
- **`.env`**: Local development (outside Docker)
- **`.env.docker`**: Docker container environment
- **`docker-compose.yml`**: Overrides specific variables (like APP_KEY)

## Production Notes

This setup is production-ready with:
- ✅ NGINX optimized for Laravel
- ✅ Security headers configured
- ✅ Asset compression (Gzip)
- ✅ Long-term asset caching
- ✅ Proper PHP-FPM configuration
- ✅ Redis for high-performance caching
- ✅ Optimized Docker builds