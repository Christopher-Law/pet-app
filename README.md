# Pet Management App

A Laravel-based pet registration application demonstrating clean architecture and design patterns.

## 🚀 Quick Start

**Prerequisites:** Docker & Docker Compose

### Production Mode (Built Assets)
```bash
# 1. Clone and start the application
git clone <repository-url> pet-app
cd pet-app
docker-compose up -d --build

# 2. Access the application
open http://localhost:8000
```

### Development Mode (Live Asset Compilation)
```bash
# 1. Clone and start the application in development mode
git clone <repository-url> pet-app
cd pet-app
docker-compose -f docker-compose.dev.yml up -d --build

# 2. Access the application
open http://localhost:8000
```

**Development mode includes:**
- ✅ Live asset compilation with Vite dev server
- ✅ Hot Module Replacement (HMR) for instant updates
- ✅ No need to rebuild assets when making changes
- ✅ Vite dev server accessible at http://localhost:5173

**Production mode includes:**
- ✅ Pre-built optimized assets
- ✅ Faster startup time
- ✅ Production-ready configuration

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

### Local Testing
```bash
# Run all tests
docker-compose exec app php artisan test

# Run specific test suites
docker-compose exec app php artisan test --testsuite=Unit
docker-compose exec app php artisan test --testsuite=Feature
```

### CI/CD Testing
The project includes comprehensive GitHub Actions workflows for continuous integration and deployment:

#### 🔄 CI Pipeline (`.github/workflows/ci.yml`)
- **PHP Testing**: Runs Pest tests with coverage reporting
- **Code Quality**: Laravel Pint for code style formatting
- **Docker Build**: Tests Docker image build and functionality
- **Security Scan**: Composer audit and security checks

#### 🚀 CD Pipeline (`.github/workflows/cd.yml`)
- **Container Registry**: Builds and pushes Docker images to GitHub Container Registry
- **Staging Deployment**: Automatic deployment to staging on main branch pushes
- **Production Release**: Creates GitHub releases and deploys to production on version tags
- **Multi-architecture Support**: Supports multiple Docker architectures

#### 🐳 Docker Testing (`.github/workflows/docker-test.yml`)
- **Container Testing**: Full Docker Compose environment testing
- **Service Integration**: Tests Redis, Nginx, and PHP-FPM integration
- **Security Scanning**: Trivy vulnerability scanning for Docker images
- **Endpoint Testing**: Validates application endpoints and functionality

#### 📊 Workflow Triggers
- **CI**: Runs on push/PR to `main` and `develop` branches
- **CD**: Runs on push to `main` branch and version tags (`v*`)
- **Docker Tests**: Runs on push/PR to `main` and `develop` branches

#### 🔧 Additional CI/CD Features
- **Dependabot**: Automated dependency updates for Composer, npm, and GitHub Actions
- **Security Scanning**: Trivy vulnerability scanning for Docker images

## 🔧 Development Commands

### Production Mode
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

### Development Mode
```bash
# View logs
docker-compose -f docker-compose.dev.yml logs -f

# Access PHP container
docker-compose -f docker-compose.dev.yml exec app bash

# Access Vite container
docker-compose -f docker-compose.dev.yml exec vite bash

# Reset database
docker-compose -f docker-compose.dev.yml exec app php artisan migrate:fresh --seed

# Clear caches
docker-compose -f docker-compose.dev.yml exec app php artisan optimize:clear

# Rebuild assets (if needed)
docker-compose -f docker-compose.dev.yml exec vite npm run build
```