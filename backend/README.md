# E-Solat THP Backend - Laravel API

A robust RESTful API built with Laravel 13, serving as the backbone for the E-Solat THP application. This backend provides comprehensive Islamic prayer time management, user authentication, and community services.

## 🏗️ Laravel Architecture Overview

This backend follows Laravel's best practices and modern PHP standards:

- **MVC Pattern**: Models, Views (API responses), Controllers
- **Service Layer**: Business logic separation
- **Repository Pattern**: Data access abstraction
- **API Resource**: Consistent API responses
- **Middleware**: Request filtering and authentication
- **Events & Listeners**: Decoupled system events

## 📁 Backend Directory Structure

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # API Controllers
│   │   ├── Middleware/       # Custom Middleware
│   │   ├── Requests/         # Form Request Validation
│   │   └── Resources/        # API Resource Transformers
│   ├── Models/               # Eloquent Models
│   ├── Providers/            # Service Providers
│   └── Exceptions/           # Custom Exception Handlers
├── config/                   # Laravel Configuration Files
├── database/
│   ├── migrations/           # Database Schema Migrations
│   ├── seeders/             # Database Seeders
│   └── factories/           # Model Factories for Testing
├── routes/
│   ├── api.php              # API Routes
│   ├── web.php              # Web Routes (if any)
│   └── channels.php         # Broadcasting Channels
├── storage/                 # Application Storage
├── tests/                   # Unit & Feature Tests
├── .env                     # Environment Configuration
├── artisan                  # Laravel CLI Tool
└── composer.json            # PHP Dependencies
```

## 🔧 Core Technologies & Packages

### Laravel Framework (13.0)

- **PHP Version**: 8.3+
- **Routing**: Powerful and expressive routing system
- **Eloquent ORM**: Advanced database ORM
- **Blade Templates**: (For API responses/emails)
- **Artisan**: Command-line interface

### Authentication & Security

- **Laravel Sanctum**: API token authentication
- **Hashing**: Secure password hashing
- **CSRF Protection**: Cross-site request forgery protection
- **Rate Limiting**: API rate limiting
- **Validation**: Robust input validation

### Development Tools

- **Laravel Pint**: Code style fixer
- **PHPUnit**: Unit and feature testing
- **Laravel Tinker**: Interactive REPL
- **Migration System**: Database version control

## 🚀 API Endpoints Overview

### Authentication Endpoints

```
POST   /api/auth/register     # User registration
POST   /api/auth/login        # User login
POST   /api/auth/logout       # User logout
POST   /api/auth/refresh      # Refresh token
GET    /api/auth/profile      # Get user profile
PUT    /api/auth/profile      # Update user profile
```

### Prayer Times Endpoints

```
GET    /api/prayer-times      # Get prayer times
GET    /api/prayer-times/{date}  # Get specific date
POST   /api/prayer-times      # Create prayer time (admin)
PUT    /api/prayer-times/{id} # Update prayer time (admin)
DELETE /api/prayer-times/{id} # Delete prayer time (admin)
```

### Islamic Calendar Endpoints

```
GET    /api/calendar          # Get Islamic calendar
GET    /api/calendar/{month}  # Get specific month
POST   /api/calendar/events   # Create event (admin)
```

### Community Endpoints

```
GET    /api/announcements     # Get announcements
POST   /api/announcements     # Create announcement (admin)
GET    /api/events            # Get community events
POST   /api/events            # Create event (admin)
```

### Admin Endpoints

```
GET    /api/admin/users       # User management
POST   /api/admin/users       # Create user
PUT    /api/admin/users/{id}  # Update user
DELETE /api/admin/users/{id}  # Delete user
GET    /api/admin/logs        # System logs
```

## 📊 Database Schema

### Core Tables

#### Users & Authentication

- **users**: User accounts and profiles
- **personal_access_tokens**: Sanctum API tokens
- **password_resets**: Password reset tokens

#### Prayer Times System

- **prayer_times**: Daily prayer schedules
- **locations**: Prayer location settings
- **calculation_methods**: Prayer time calculation methods

#### Islamic Calendar

- **islamic_calendar**: Hijri calendar data
- **islamic_events**: Important Islamic events
- **ramadan_schedules**: Ramadan-specific schedules

#### Community Management

- **announcements**: Community announcements
- **events**: Community events
- **event_attendees**: Event participation

#### System Management

- **system_settings**: Application configuration
- **audit_logs**: System audit trail
- **activity_logs**: User activity tracking

## 🛡️ Security Features

### Authentication

- **API Tokens**: Sanctum-based token authentication
- **Token Expiration**: Configurable token lifetimes
- **Ability Checks**: Role-based permissions
- **Rate Limiting**: Prevent abuse

### Data Protection

- **Input Validation**: Comprehensive request validation
- **SQL Injection Prevention**: Eloquent ORM protection
- **XSS Protection**: Output escaping
- **CSRF Protection**: Built-in CSRF tokens

### API Security

- **CORS Configuration**: Cross-origin resource sharing
- **HTTPS Enforcement**: SSL/TLS requirements
- **API Versioning**: Version-controlled endpoints
- **Request Logging**: Security audit trail

## 🧪 Testing Strategy

### Feature Tests

- **Authentication Tests**: Login, registration, token management
- **API Endpoint Tests**: All endpoints with various scenarios
- **Permission Tests**: Role-based access control
- **Validation Tests**: Input validation scenarios

### Unit Tests

- **Model Tests**: Eloquent model relationships and methods
- **Service Tests**: Business logic validation
- **Utility Tests**: Helper functions and utilities

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test --filter UserTest

# Run with coverage
php artisan test --coverage
```

## 🔧 Configuration

### Environment Variables

```env
# Application
APP_NAME="E-Solat THP"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_solat_thp
DB_USERNAME=root
DB_PASSWORD=

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Mail
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Key Configuration Files

- **config/auth.php**: Authentication configuration
- **config/database.php**: Database connections
- **config/sanctum.php**: API token settings
- **config/cors.php**: Cross-origin settings
- **config/cache.php**: Caching configuration

## 📝 Development Guidelines

### Code Standards

- **PSR-12**: PHP coding standards
- **Laravel Conventions**: Framework-specific conventions
- **Type Declarations**: Strict typing where possible
- **Documentation**: PHPDoc comments for all methods

### API Design Principles

- **RESTful**: Follow REST conventions
- **Consistent Responses**: Standardized API response format
- **Error Handling**: Proper HTTP status codes and error messages
- **Versioning**: API versioning strategy

### Best Practices

- **Single Responsibility**: Each class has one purpose
- **Dependency Injection**: Use Laravel's service container
- **Validation**: Use Form Request classes
- **Error Handling**: Custom exception handlers

## 🚀 Deployment

### Production Setup

```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --force
php artisan db:seed --force

# Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

# File permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### Performance Optimization

- **Database Indexing**: Proper indexes for queries
- **Query Optimization**: Eager loading, query caching
- **Redis Caching**: Application-level caching
- **OPcache**: PHP opcode caching
- **CDN**: Static asset delivery

### Monitoring & Logging

- **Application Logs**: Laravel's logging system
- **Error Tracking**: Integration with error services
- **Performance Monitoring**: Query time tracking
- **Health Checks**: Application health endpoints

## 📚 Additional Documentation

- **[API Documentation](./API_DOCUMENTATION.md)**: Complete API reference
- **[Authentication Guide](./AUTHENTICATION.md)**: Security and authentication details
- **[Setup Instructions](./SETUP.md)**: Detailed setup guide
- **[Advanced Tips](./TIPS_ADVANCED.md)**: Advanced usage and optimization

## 🛠️ Common Commands

### Development

```bash
# Start development server
php artisan serve

# Database operations
php artisan migrate
php artisan db:seed
php artisan migrate:fresh --seed

# Cache operations
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Code quality
composer pint
php artisan test
```

### Production

```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Queue management
php artisan queue:work
php artisan queue:failed-table
```

## 🤝 Contributing to Backend

1. Follow Laravel coding standards
2. Write tests for new features
3. Update documentation
4. Use feature branches
5. Submit pull requests with clear descriptions

---

**E-Solat THP Backend** - A modern, secure, and scalable Laravel API for Islamic prayer services.
