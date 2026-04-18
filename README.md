# E-Solat THP (Tahap Hijrah Putrajaya)

A comprehensive web application for managing Islamic prayer times and related services for the Tahap Hijrah Putrajaya community. This project provides accurate prayer schedules, Islamic calendar information, and community management features.

## 🏗️ Project Architecture

This is a **full-stack web application** built with modern technologies:

- **Backend**: Laravel 13 (PHP 8.3) - RESTful API with authentication
- **Frontend**: Vue.js 3.5 + Vite - Modern SPA with TailwindCSS
- **Database**: MySQL with comprehensive schema for Islamic data

## 📁 Project Structure

```
e-Solat THP/
├── backend/                 # Laravel API Backend
│   ├── app/                # Application logic
│   │   ├── Http/           # Controllers, Middleware, Requests
│   │   ├── Models/         # Eloquent Models
│   │   └── Providers/      # Service Providers
│   ├── config/             # Configuration files
│   ├── database/           # Migrations, Seeders, Factories
│   ├── resources/          # Views, Lang, Assets
│   ├── routes/             # API & Web Routes
│   ├── storage/            # App storage (logs, cache, uploads)
│   └── tests/              # Unit & Feature Tests
├── frontend/               # Vue.js Frontend
│   ├── src/
│   │   ├── components/     # Reusable Vue Components
│   │   ├── layouts/        # Layout Components
│   │   ├── router/         # Vue Router Configuration
│   │   ├── services/       # API Services
│   │   ├── stores/         # State Management
│   │   ├── views/          # Page Components
│   │   ├── App.vue         # Root Component
│   │   └── main.js         # Entry Point
│   ├── dist/               # Build Output
│   └── public/             # Static Assets
├── e_solat_thp.sql         # Database Schema & Initial Data
└── README.md               # This file
```

## 🚀 Key Features

### 🕌 Prayer Time Management
- Accurate prayer time calculations for Putrajaya region
- Daily, weekly, and monthly prayer schedules
- Imsak and Syuruq timing
- Qibla direction information

### 📅 Islamic Calendar
- Hijri calendar integration
- Important Islamic dates and events
- Ramadan scheduling support
- Holiday management

### 👥 Community Features
- User management and authentication
- Role-based access control
- Community announcements
- Event management

### 📱 Modern UI/UX
- Responsive design for all devices
- Progressive Web App (PWA) ready
- Dark/Light theme support
- Accessibility compliant

## 🛠️ Technology Stack

### Backend (Laravel)
- **Framework**: Laravel 13.0
- **PHP Version**: 8.3+
- **Authentication**: Laravel Sanctum
- **Database**: MySQL/MariaDB
- **API Documentation**: Comprehensive API docs included
- **Testing**: PHPUnit with feature tests

### Frontend (Vue.js)
- **Framework**: Vue.js 3.5 (Composition API)
- **Build Tool**: Vite 8.0
- **Routing**: Vue Router 5.0
- **Styling**: TailwindCSS 3.4
- **HTTP Client**: Axios
- **State Management**: Pinia (if implemented)

### Development Tools
- **Code Quality**: Laravel Pint, ESLint
- **Testing**: PHPUnit, Jest (if implemented)
- **Version Control**: Git
- **Package Management**: Composer (PHP), npm (Node.js)

## 📋 Prerequisites

Before running this application, ensure you have:

- **PHP 8.3+** with required extensions
- **Composer** (PHP package manager)
- **Node.js 18+** and **npm**
- **MySQL** or **MariaDB** database server
- **Git** for version control

## 🚀 Quick Start

### 1. Clone the Repository
```bash
git clone <repository-url>
cd e-Solat THP
```

### 2. Backend Setup
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### 3. Frontend Setup
```bash
cd ../frontend
npm install
npm run dev
```

### 4. Start Development Servers
```bash
# Backend (in backend directory)
php artisan serve

# Frontend (in frontend directory)
npm run dev
```

## 📚 Detailed Documentation

For detailed information about each component:

- **[Backend Documentation](./backend/README.md)** - Laravel API details
- **[Frontend Documentation](./frontend/README.md)** - Vue.js application details
- **[API Documentation](./backend/API_DOCUMENTATION.md)** - Complete API reference
- **[Authentication Guide](./backend/AUTHENTICATION.md)** - Security & auth details
- **[Setup Instructions](./backend/SETUP.md)** - Detailed setup guide

## 🗄️ Database

The project includes a comprehensive database schema (`e_solat_thp.sql`) with:

- **Users & Authentication**: User management, roles, permissions
- **Prayer Times**: Schedules, locations, calculations
- **Islamic Calendar**: Hijri dates, events, holidays
- **Community**: Announcements, events, management
- **System**: Logs, settings, configurations

## 🔧 Development Commands

### Backend (Laravel)
```bash
# Development server
php artisan serve

# Database operations
php artisan migrate
php artisan db:seed
php artisan migrate:fresh --seed

# Testing
php artisan test
composer test

# Code quality
composer pint
```

### Frontend (Vue.js)
```bash
# Development
npm run dev

# Production build
npm run build

# Preview production build
npm run preview

# Dependencies
npm install
npm update
```

## 🌐 Deployment

### Production Deployment
1. Configure environment variables
2. Run `composer install --optimize-autoloader --no-dev`
3. Run `npm run build` in frontend directory
4. Set up web server (Nginx/Apache) with SSL
5. Configure database and run migrations
6. Set up queue workers if needed

### Environment Variables
Key environment variables to configure:
- `DB_*` - Database connection
- `APP_*` - Application settings
- `MAIL_*` - Email configuration
- `SANCTUM_*` - API authentication

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Support

For support and questions:
- Check the documentation in respective folders
- Review existing issues
- Contact the development team

## 🙏 Acknowledgments

- Islamic development authorities for prayer time calculations
- Laravel and Vue.js communities
- Contributors and testers
- Tahap Hijrah Putrajaya community

---

**E-Solat THP** - Modern Islamic prayer times management for the digital age.
