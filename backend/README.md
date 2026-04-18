# E-Solat THP Backend - Laravel API

API RESTful yang robust dibangun dengan Laravel 13, berfungsi sebagai backbone untuk aplikasi E-Solat THP. Backend ini menyediakan sistem manajemen absensi solat sekolah, autentikasi pengguna, dan layanan administrasi siswa.

## 🏗️ Arsitektur Laravel

Backend ini mengikuti best practices Laravel dan standar PHP modern:

- **MVC Pattern**: Models, Views (API responses), Controllers
- **Service Layer**: Pemisahan business logic
- **Repository Pattern**: Abstraksi akses data
- **API Resource**: Respon API yang konsisten
- **Middleware**: Filtering request dan autentikasi
- **Events & Listeners**: Event sistem yang terpisah

## 📁 Struktur Directory Backend

```
backend/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/          # API Controllers
│   │           ├── AuthController.php      # Autentikasi user
│   │           ├── SiswaController.php    # Manajemen data siswa
│   │           ├── PetugasController.php   # Manajemen petugas
│   │           ├── KelasController.php     # Manajemen kelas
│   │           ├── AbsensiController.php  # Sistem absensi
│   │           └── Controller.php         # Base controller
│   ├── Models/               # Eloquent Models
│   │   ├── User.php         # Model user sistem
│   │   ├── Siswa.php        # Model data siswa
│   │   ├── Petugas.php      # Model data petugas
│   │   ├── Kelas.php        # Model data kelas
│   │   └── Absensi.php      # Model data absensi
│   └── Providers/            # Service Providers
├── config/                   # File Konfigurasi Laravel
├── database/
│   ├── migrations/           # Migrations Schema Database
│   │   ├── create_users_table.php
│   │   ├── create_siswa_table.php
│   │   ├── create_petugas_table.php
│   │   ├── create_kelas_table.php
│   │   └── create_absensi_table.php
│   ├── seeders/             # Database Seeders
│   └── factories/           # Model Factories untuk Testing
├── routes/
│   ├── api.php              # Routes API endpoints
│   ├── web.php              # Web Routes (jika ada)
│   └── channels.php         # Broadcasting Channels
├── storage/                 # Penyimpanan Aplikasi
├── tests/                   # Unit & Feature Tests
├── .env                     # Konfigurasi Environment
├── artisan                  # Laravel CLI Tool
└── composer.json            # Dependencies PHP
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

### Authentication Endpoints (Public)

```
POST   /api/register          # Registrasi user baru
POST   /api/login             # Login user
```

### Absensi Endpoints

```
POST   /api/absensi/public    # Absensi siswa tanpa login (NIS)
GET    /api/absensi           # Get data absensi (auth required)
GET    /api/absensi/by-date/{tanggal}  # Get absensi per tanggal
GET    /api/absensi/siswa/{nis}        # Get absensi per siswa
```

### Siswa Management Endpoints (Auth Required)

```
GET    /api/siswa             # Get all siswa
POST   /api/siswa             # Create new siswa
GET    /api/siswa/{nis}       # Get siswa by NIS
PUT    /api/siswa/{nis}       # Update siswa data
DELETE /api/siswa/{nis}       # Delete siswa
POST   /api/siswa/import      # Import data siswa (CSV/Excel)
```

### Petugas Management Endpoints (Auth Required)

```
GET    /api/petugas           # Get all petugas
POST   /api/petugas           # Create new petugas
GET    /api/petugas/{id}      # Get petugas by ID
PUT    /api/petugas/{id}      # Update petugas data
DELETE /api/petugas/{id}      # Delete petugas
```

### Kelas Management Endpoints (Auth Required)

```
GET    /api/kelas             # Get all kelas
POST   /api/kelas             # Create new kelas
GET    /api/kelas/{id}        # Get kelas by ID
PUT    /api/kelas/{id}        # Update kelas data
DELETE /api/kelas/{id}        # Delete kelas
```

### User Profile Endpoints (Auth Required)

```
GET    /api/me                # Get current user profile
POST   /api/logout            # Logout user
```

## 📊 Database Schema

### Core Tables

#### Users & Authentication

- **users**: Akun pengguna sistem (admin, petugas)
- **personal_access_tokens**: Token API Sanctum
- **password_resets**: Token reset password

#### Sistem Absensi

- **absensi**: Data absensi solat siswa
    - id_absensi (Primary Key)
    - nis (Foreign Key ke tabel siswa)
    - tanggal (Tanggal absensi)
    - waktu_absen (Waktu pencatatan)
    - id_petugas (Foreign Key ke tabel petugas, nullable)

#### Manajemen Siswa

- **siswa**: Data lengkap siswa
    - nis (Primary Key, string)
    - nama_siswa (Nama lengkap)
    - jenis_kelamin (L/P)
    - id_kelas (Foreign Key ke tabel kelas)

#### Manajemen Petugas

- **petugas**: Data petugas absensi
    - id_petugas (Primary Key)
    - nama_petugas (Nama lengkap)
    - email (Email login)
    - password (Hashed password)

#### Manajemen Kelas

- **kelas**: Data kelas dan jurusan
    - id_kelas (Primary Key)
    - nama_kelas (Nama kelas)
    - jurusan (Jurusan/Program studi)

#### System Management

- **cache**: Cache sistem Laravel
- **jobs**: Queue jobs Laravel
- **failed_jobs**: Jobs yang gagal dieksekusi

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

1. Ikuti Laravel coding standards
2. Tulis tests untuk fitur baru
3. Update dokumentasi
4. Gunakan feature branches
5. Submit pull requests dengan deskripsi jelas

---

**E-Solat THP Backend** - API Laravel yang modern, aman, dan skalabel untuk sistem absensi solat sekolah.
