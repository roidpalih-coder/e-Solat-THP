# Setup Backend E-SOLAT THP

Panduan lengkap untuk setup dan menjalankan backend Laravel E-SOLAT THP.

## Prerequisites

-   PHP 8.2 atau lebih tinggi
-   Composer
-   MySQL atau database lainnya
-   Git (opsional)

## Install & Setup

### 1. Clone/Download Project

```bash
cd "d:/Tugas Sekolah/PIC (Pak Dandi)/project final/e-solat THP"
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Setup .env File

Copy file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Edit file `.env` dan set database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_solat_thp
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Create Database

```bash
# Login ke MySQL
mysql -u root -p

# Run commands di MySQL CLI
CREATE DATABASE e_solat_thp;
EXIT;
```

### 6. Run Migrations

```bash
php artisan migrate
```

### 7. Seed Database (Optional - untuk test data)

```bash
php artisan db:seed
```

### 8. Run Development Server

```bash
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

---

## Struktur Project

```
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── KelasController.php
│   │       ├── SiswaController.php
│   │       ├── PetugasController.php
│   │       └── AbsensiController.php
│   └── Models/
│       ├── Kelas.php
│       ├── Siswa.php
│       ├── Petugas.php
│       └── Absensi.php
├── database/
│   ├── migrations/
│   │   ├── 2026_04_11_032950_create_kelas_table.php
│   │   ├── 2026_04_11_032957_create_siswa_table.php
│   │   ├── 2026_04_11_033005_create_petugas_table.php
│   │   └── 2026_04_11_033014_create_absensi_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── routes/
│   └── api.php
├── API_DOCUMENTATION.md
└── SETUP.md
```

---

## API Endpoints

Semua API endpoints tersedia di `routes/api.php`.

### Base URL

```
http://localhost:8000/api
```

### Resource Endpoints

#### 1. Kelas

```
GET    /api/kelas              # Get all kelas
POST   /api/kelas              # Create new kelas
GET    /api/kelas/{id_kelas}   # Get single kelas
PUT    /api/kelas/{id_kelas}   # Update kelas
DELETE /api/kelas/{id_kelas}   # Delete kelas
```

#### 2. Siswa

```
GET    /api/siswa              # Get all siswa
POST   /api/siswa              # Create new siswa
GET    /api/siswa/{nis}        # Get single siswa
PUT    /api/siswa/{nis}        # Update siswa
DELETE /api/siswa/{nis}        # Delete siswa
```

#### 3. Petugas

```
GET    /api/petugas            # Get all petugas
POST   /api/petugas            # Create new petugas
GET    /api/petugas/{id}       # Get single petugas
PUT    /api/petugas/{id}       # Update petugas
DELETE /api/petugas/{id}       # Delete petugas
```

#### 4. Absensi

```
GET    /api/absensi                          # Get all absensi
POST   /api/absensi                          # Create new absensi
GET    /api/absensi/{id_absensi}             # Get single absensi
PUT    /api/absensi/{id_absensi}             # Update absensi
DELETE /api/absensi/{id_absensi}             # Delete absensi
GET    /api/absensi/by-date/{tanggal}        # Get absensi by date
GET    /api/absensi/siswa/{nis}              # Get absensi by siswa
```

Lihat `API_DOCUMENTATION.md` untuk detail lengkap dan contoh request/response.

---

## Database Schema

### Relasi Tabel

```
┌──────────┐
│ kelas    │
├──────────┤
│ id_kelas │◄─────┐
│ nama     │      │
└──────────┘      │
                  │ (one-to-many)
                  │
              ┌─────────────┐
              │ siswa       │
              ├─────────────┤
              │ nis         │
              │ nama_siswa  │
              │ jenis_kelamin
              │ id_kelas    │───┘
              └─────────────┘
                  │
                  │ (one-to-many)
                  │
              ┌──────────────┐                ┌──────────┐
              │ absensi      │                │ petugas  │
              ├──────────────┤                ├──────────┤
              │ id_absensi   │                │ id_petugas
              │ nis          │───┐        ┌───│ username │
              │ tanggal      │   │        │   │ password │
              │ waktu_absen  │   └────────┤───│          │
              │ id_petugas   │◄──────────┘   └──────────┘
              └──────────────┘

```

---

## Testing API dengan cURL

### Test Get All Kelas

```bash
curl http://localhost:8000/api/kelas
```

### Test Create Kelas

```bash
curl -X POST http://localhost:8000/api/kelas \
  -H "Content-Type: application/json" \
  -d '{"nama_kelas":"XII A"}'
```

### Test Create Siswa

```bash
curl -X POST http://localhost:8000/api/siswa \
  -H "Content-Type: application/json" \
  -d '{
    "nis": "001",
    "nama_siswa": "Ahmad",
    "jenis_kelamin": "L",
    "id_kelas": 1
  }'
```

### Test Create Petugas

```bash
curl -X POST http://localhost:8000/api/petugas \
  -H "Content-Type: application/json" \
  -d '{
    "username": "admin1",
    "password": "password123"
  }'
```

### Test Create Absensi

```bash
curl -X POST http://localhost:8000/api/absensi \
  -H "Content-Type: application/json" \
  -d '{
    "nis": "001",
    "tanggal": "2026-04-11",
    "waktu_absen": "07:30:00",
    "id_petugas": 1
  }'
```

---

## Testing dengan Postman

1. Import atau buat collection baru di Postman
2. Set base URL ke `http://localhost:8000/api`
3. Create requests untuk setiap endpoint
4. Test CRUD operations untuk semua resources

Contoh request structure:

```
Method: GET/POST/PUT/DELETE
URL: http://localhost:8000/api/[resource]/[id?]
Headers:
  - Content-Type: application/json
Body: (JSON untuk POST/PUT)
```

---

## Troubleshooting

### Error: "Database does not exist"

```bash
# Create database terlebih dahulu
mysql -u root -p
> CREATE DATABASE e_solat_thp;
> EXIT;

# Kemudian run migrations
php artisan migrate
```

### Error: "Column not found"

```bash
# Pastikan semua migrations sudah di-run
php artisan migrate

# Atau rollback dan re-run
php artisan migrate:refresh
```

### Error: "Connection refused"

```bash
# Pastikan MySQL server running
# Laravel development server harus di-run terpisah
php artisan serve
```

### 500 error saat create data

```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Validasi bahwa data yang dikirim sesuai dengan requirement
```

---

## Production Deployment

Untuk deployment ke production:

1. Set `.env` variable ke production mode:

    ```
    APP_ENV=production
    APP_DEBUG=false
    ```

2. Generate optimized autoloader:

    ```bash
    composer install --optimize-autoloader --no-dev
    ```

3. Cache configuration:

    ```bash
    php artisan config:cache
    ```

4. Setup proper web server (Nginx/Apache)
5. Setup SSL certificate
6. Setup proper database dan environment variables

---

## Notes

-   Semua endpoint mengembalikan JSON response
-   Validation errors mengembalikan 422 status code
-   Resource not found mengembalikan 404 status code
-   Password di-hash menggunakan bcrypt
-   Foreign key constraints dengan cascade delete

---

## Support

Untuk bantuan lebih lanjut, lihat dokumentasi resmi:

-   Laravel Documentation: https://laravel.com/docs
-   Laravel API: https://laravel.com/api/11.x
