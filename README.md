# Pet App

A Laravel-based pet management application with Docker support.

## Quick Start

1. Create SQLite database:
```bash
touch database/database.sqlite
```

2. Build and start containers:
```bash
docker-compose up -d --build
```

3. Access the application at `http://localhost:8000`

## Services

- **NGINX**: Web server (Port 8000)
- **PHP-FPM**: Laravel application 
- **Redis**: Cache and session storage (Port 6379)

## Useful Commands

```bash
# Container management
docker-compose up -d          # Start containers
docker-compose down           # Stop containers
docker-compose logs -f app    # View app logs
docker-compose exec app bash  # Access app container

# Laravel commands (inside container)
docker-compose exec app php artisan migrate
docker-compose exec app php artisan tinker
docker-compose exec app php artisan cache:clear

# Development
docker-compose exec app npm run dev  # Watch assets
```

## Environment

The application uses:
- **Database**: SQLite
- **Cache**: Redis
- **Sessions**: Redis
- **Queue**: Database