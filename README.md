# E-Solat THP (Sistem Absensi Solat)

Sistem manajemen absensi solat digital untuk sekolah Tahap Hijrah Putrajaya. Aplikasi ini memfasilitasi pencatatan kehadiran siswa dalam ibadah solat berjamaah secara digital, transparan, dan efisien.

## 🏗️ Arsitektur Proyek

Aplikasi **full-stack modern** yang dibangun dengan teknologi terkini:

- **Backend**: Laravel 13 (PHP 8.3) - RESTful API dengan autentikasi
- **Frontend**: Vue.js 3.5 + Vite - SPA modern dengan TailwindCSS
- **Database**: MySQL dengan skema untuk manajemen absensi sekolah

## 📁 Struktur Proyek

```
e-Solat THP/
├── backend/                 # Laravel API Backend
│   ├── app/                # Logika aplikasi
│   │   ├── Http/           # Controllers, Middleware, Requests
│   │   │   └── Controllers/Api/  # API Controllers
│   │   │       ├── AuthController.php    # Autentikasi
│   │   │       ├── SiswaController.php  # Manajemen siswa
│   │   │       ├── PetugasController.php # Manajemen petugas
│   │   │       ├── KelasController.php   # Manajemen kelas
│   │   │       └── AbsensiController.php # Sistem absensi
│   │   ├── Models/         # Eloquent Models
│   │   │   ├── User.php           # Model user
│   │   │   ├── Siswa.php          # Model siswa
│   │   │   ├── Petugas.php        # Model petugas
│   │   │   ├── Kelas.php          # Model kelas
│   │   │   └── Absensi.php        # Model absensi
│   │   └── Providers/      # Service Providers
│   ├── config/             # File konfigurasi
│   ├── database/           # Migrations, Seeders, Factories
│   │   └── migrations/     # Schema database
│   │       ├── create_users_table.php
│   │       ├── create_siswa_table.php
│   │       ├── create_petugas_table.php
│   │       ├── create_kelas_table.php
│   │       └── create_absensi_table.php
│   ├── resources/          # Views, Lang, Assets
│   ├── routes/             # API & Web Routes
│   │   └── api.php         # Route API endpoints
│   ├── storage/            # Penyimpanan aplikasi
│   └── tests/              # Unit & Feature Tests
├── frontend/               # Vue.js Frontend
│   ├── src/
│   │   ├── components/     # Komponen Vue reusable
│   │   ├── layouts/        # Layout Components
│   │   ├── router/         # Konfigurasi Vue Router
│   │   ├── services/       # API Services
│   │   ├── stores/         # State Management
│   │   ├── views/          # Komponen Halaman
│   │   │   ├── HomeView.vue        # Halaman utama
│   │   │   ├── LoginView.vue       # Halaman login
│   │   │   ├── RegisterView.vue    # Halaman registrasi
│   │   │   ├── AbsensiView.vue     # Halaman absensi siswa
│   │   │   └── admin/             # Halaman admin
│   │   │       ├── DashboardView.vue     # Dashboard admin
│   │   │       ├── DataSiswaView.vue      # Manajemen data siswa
│   │   │       ├── TambahSiswaView.vue    # Tambah siswa
│   │   │       ├── ImportSiswaView.vue    # Import data siswa
│   │   │       └── AbsensiSiswaView.vue   # Laporan absensi
│   │   ├── App.vue         # Root Component
│   │   └── main.js         # Entry Point
│   ├── dist/               # Build Output
│   └── public/             # Static Assets
├── e_solat_thp.sql         # Database Schema & Initial Data
└── README.md               # File ini
```

## 🚀 Fitur Utama

### � Sistem Absensi Digital

- Pencatatan absensi solat real-time
- Input NIS siswa yang praktis
- Validasi data otomatis
- Riwayat absensi harian

### 👥 Manajemen Siswa

- Data lengkap siswa (NIS, nama, kelas, jenis kelamin)
- Import data siswa massal (CSV/Excel)
- Manajemen kelas dan jurusan
- Profil siswa terintegrasi

### �‍💼 Manajemen Petugas

- Data petugas absensi
- Hak akses berbasis peran
- Validasi absensi oleh petugas

### 📈 Laporan & Analitik

- Dashboard admin dengan statistik lengkap
- Laporan kehadiran per siswa
- Rekapitulasi absensi per kelas
- Export data laporan

### � Keamanan & Autentikasi

- Login sistem untuk admin dan petugas
- Token-based authentication (Sanctum)
- Role-based access control
- Validasi input data

### 📱 UI/UX Modern

- Responsive design untuk semua perangkat
- Material Design dengan TailwindCSS
- Dark/Light theme support
- User-friendly interface

## 🛠️ Technology Stack

### Backend (Laravel)

- **Framework**: Laravel 13.0
- **PHP Version**: 8.3+
- **Authentication**: Laravel Sanctum
- **Database**: MySQL/MariaDB
- **API Documentation**: API docs included
- **Testing**: PHPUnit dengan feature tests

### Frontend (Vue.js)

- **Framework**: Vue.js 3.5 (Composition API)
- **Build Tool**: Vite 8.0
- **Routing**: Vue Router 5.0
- **Styling**: TailwindCSS 3.4
- **HTTP Client**: Axios
- **Data Processing**: PapaParse untuk CSV import

### Development Tools

- **Code Quality**: Laravel Pint
- **Testing**: PHPUnit
- **Version Control**: Git
- **Package Management**: Composer (PHP), npm (Node.js)

## 📋 Prerequisites

Sebelum menjalankan aplikasi ini, pastikan Anda memiliki:

- **PHP 8.3+** dengan ekstensi yang diperlukan
- **Composer** (PHP package manager)
- **Node.js 18+** dan **npm**
- **MySQL** atau **MariaDB** database server
- **Git** untuk version control

## 🚀 Quick Start

### 1. Clone Repository

```bash
git clone https://github.com/roidpalih-coder/e-Solat-THP.git
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
# Backend (di folder backend)
php artisan serve

# Frontend (di folder frontend)
npm run dev
```

## 📚 Dokumentasi Lengkap

Untuk informasi detail tentang setiap komponen:

- **[Backend Documentation](./backend/README.md)** - Detail Laravel API
- **[Frontend Documentation](./frontend/README.md)** - Detail aplikasi Vue.js
- **[API Documentation](./backend/API_DOCUMENTATION.md)** - Referensi API lengkap
- **[Authentication Guide](./backend/AUTHENTICATION.md)** - Detail keamanan & autentikasi
- **[Setup Instructions](./backend/SETUP.md)** - Panduan setup detail

## 🗄️ Database

Proyek ini menyertakan skema database komprehensif (`e_solat_thp.sql`) dengan:

- **Users & Authentication**: Manajemen user, roles, permissions
- **Data Siswa**: Informasi lengkap siswa (NIS, nama, kelas, jenis kelamin)
- **Data Petugas**: Informasi petugas absensi
- **Data Kelas**: Manajemen kelas dan jurusan
- **Absensi**: Riwayat absensi solat siswa
- **System**: Logs, settings, konfigurasi

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

1. Konfigurasi environment variables
2. Jalankan `composer install --optimize-autoloader --no-dev`
3. Jalankan `npm run build` di folder frontend
4. Setup web server (Nginx/Apache) dengan SSL
5. Konfigurasi database dan jalankan migrations
6. Setup queue workers jika diperlukan

### Environment Variables

Variabel environment penting untuk dikonfigurasi:

- `DB_*` - Koneksi database
- `APP_*` - Pengaturan aplikasi
- `MAIL_*` - Konfigurasi email
- `SANCTUM_*` - API authentication

## 🤝 Contributing

1. Fork repository
2. Buat feature branch (`git checkout -b feature/amazing-feature`)
3. Commit perubahan Anda (`git commit -m 'Add amazing feature'`)
4. Push ke branch (`git push origin feature/amazing-feature`)
5. Buka Pull Request

## 📄 License

Proyek ini dilisensikan di bawah MIT License - lihat file [LICENSE](LICENSE) untuk detail.

## 📞 Support

Untuk support dan pertanyaan:

- Cek dokumentasi di folder masing-masing
- Review issues yang ada
- Hubungi development team

## 🙏 Acknowledgments

- Laravel dan Vue.js communities
- Kontributor dan tester
- Pihak sekolah Tahap Hijrah Putrajaya

---

**E-Solat THP** - Sistem absensi solat digital modern untuk era pendidikan digital.
