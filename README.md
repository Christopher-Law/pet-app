# Pet Management App

A Laravel-based pet registration application demonstrating clean architecture and design patterns.

## 🚀 Quick Start

**Prerequisites:** Docker & Docker Compose

```bash
# 1. Clone and start the application
git clone <repository-url> pet-app
cd pet-app
docker-compose up -d --build

# 2. Access the application
open http://localhost:8000
```

That's it! The application will automatically:
- ✅ Set up the database and run migrations
- ✅ Install dependencies and build assets
- ✅ Start all services (Laravel, NGINX, Redis)

## 🎯 Features

- **Pet Registration Form** with dynamic breed selection
- **Dangerous Breed Detection** with visual indicators
- **Age Calculation** and date of birth handling
- **Responsive Design** with modern UI
- **Event-Driven Architecture** with automatic alerts

## 🏗️ Architecture

**Design Patterns Implemented:**
- **Factory Pattern** - Pet creation with business rules
- **Command Pattern** - Encapsulated operations with transactions
- **Observer Pattern** - Event-driven pet creation alerts
- **Strategy Pattern** - Type-specific validation rules
- **Repository Pattern** - Clean data access abstraction

**Tech Stack:**
- **Backend:** Laravel 12 with PHP 8.4
- **Database:** SQLite with migrations
- **Cache:** Redis
- **Frontend:** Blade templates with TailwindCSS
- **Container:** Docker with NGINX

## 🧪 Testing

```bash
# Run all tests
docker-compose exec app php artisan test

# Run specific test suites
docker-compose exec app php artisan test --testsuite=Unit
docker-compose exec app php artisan test --testsuite=Feature
```

## 🔧 Development Commands

```bash
# View logs
docker-compose logs -f app

# Access container
docker-compose exec app bash

# Reset database
docker-compose exec app php artisan migrate:fresh --seed

# Clear caches
docker-compose exec app php artisan optimize:clear
```