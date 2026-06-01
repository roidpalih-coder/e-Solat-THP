# 🕌 e-Solat THP (Sistem Absensi Solat Digital)

Sistem manajemen absensi solat digital terpadu untuk sekolah **SMK THP** (Tunas Harapan Pati). Aplikasi ini memfasilitasi pencatatan kehadiran siswa dalam ibadah solat berjamaah secara digital, transparan, akurat, dan efisien.

Aplikasi ini menggunakan arsitektur **decoupled** dengan backend berbasis API (Laravel) dan frontend berbasis Single Page Application / SPA (Vue.js).

---

## 🚀 Fitur Utama

### 1. 📝 Sistem Absensi Digital Real-time
* **Portal Absensi Publik**: Siswa dapat menginput NIS (Nomor Induk Siswa) secara mandiri pada terminal absensi.
* **Verifikasi Cepat & Akurat**: Validasi data langsung terhubung ke database pusat dan mencegah absensi ganda pada hari yang sama.
* **Popup Konfirmasi Sukses**: Menampilkan dialog interaktif berisi rincian identitas siswa, waktu absen, dan status tepat waktu secara instan setelah melakukan scan/input.

### 2. 📊 Dashboard Analitik Bento Grid
* **Live Statistics Summary**: Informasi jumlah total siswa aktif, jumlah kelas, persentase kehadiran hari ini, dan jumlah kehadiran live.
* **Absensi Terbaru (Live Feed)**: Daftar 5 absensi siswa terakhir yang masuk sistem secara real-time.
* **Sistem Notifikasi/Pesan**: Pesan sistem admin untuk instruksi operasional harian.

### 3. 👥 Manajemen Data Terpadu (Admin Panel)
* **Manajemen Siswa**: CRUD (Create, Read, Update, Delete) data siswa dengan filter jenis kelamin, kelas, jurusan, serta fitur pencarian nama/NIS.
* **Manajemen Kelas & Jurusan**: Pengelompokan data siswa berdasarkan kelas dan jurusan masing-masing.
* **Import Data Massal**: Fitur import ratusan data siswa sekaligus dari file CSV menggunakan parser `PapaParse`.
* **Export Data Laporan**: Fitur export laporan harian daftar hadir siswa ke format CSV.

### 4. 🌓 Sistem Global Theme (Dark & Light Mode)
* **Real-time Theme Toggle**: Pengguna dapat mengubah tema secara instan di semua halaman (publik & admin) tanpa refresh.
* **Anti-Flicker Technology**: Script inisialisasi inline pada header HTML untuk mencegah kilatan layar (*flicker*) saat memuat preferensi tema.
* **Auto Persistence**: Pilihan tema disimpan otomatis di `localStorage` browser.

### 5. 📈 Grafik Statistik Mingguan Dinamis
* **Custom Bar Chart**: Grafik batang interaktif dari hari Senin s.d Jumat yang langsung menampilkan data kehadiran tanpa perlu di-hover.
* **Micro-Animations**: Hover effect interaktif yang memperbesar badge nilai dan mengubah warna bar saat kursor diarahkan ke grafik.

### 6. 📱 Desain Responsif & Premium
* **Material Design**: Desain UI modern dengan visual glassmorphism, sudut lengkung melingkar, dan dynamic transition.
* **Responsive Layout**: Menjamin keterbacaan dan kenyamanan akses pada perangkat Mobile, Tablet, dan Desktop dengan background gambar adaptif.

---

## 🛠️ Tech Stack

### Frontend (Vue.js SPA)
* **Core Framework**: Vue.js 3.5+ (Composition API)
* **Build Tool**: Vite 8.0
* **Routing**: Vue Router 5.0
* **Styling**: TailwindCSS 3.4 + Vanilla CSS Variables
* **HTTP Client**: Axios (dengan Interceptors untuk injeksi token JWT)
* **Data Parser**: PapaParse (untuk import CSV data siswa)
* **State Management**: Vue Reactive Refs (Shared global stores: `stores/auth.js` dan `stores/theme.js`)

### Backend (Laravel API)
* **Core Framework**: Laravel 13.0
* **Language**: PHP 8.3+
* **Authentication**: Laravel Sanctum (Token-based API authentication)
* **Database**: MySQL / MariaDB

---

## 📁 Struktur Proyek

```text
e-Solat THP/
├── backend/                    # Laravel API Backend
│   ├── app/                    # Core logic aplikasi
│   │   ├── Http/               # Controller API, Middleware, Requests
│   │   │   └── Controllers/Api/# Endpoint API e-Solat
│   │   │       ├── AuthController.php    # Autentikasi petugas
│   │   │       ├── SiswaController.php   # CRUD siswa & import CSV
│   │   │       ├── PetugasController.php # Kelola data petugas
│   │   │       ├── KelasController.php   # Kelola data kelas
│   │   │       └── AbsensiController.php # Pencatatan absensi & stat mingguan
│   │   └── Models/             # Eloquent Models (Siswa, Petugas, Kelas, Absensi, User)
│   ├── config/                 # File konfigurasi Laravel
│   ├── database/               # Migrasi skema database & seeders
│   ├── routes/                 # Definisi routing API (routes/api.php)
│   └── tests/                  # Unit & Feature Testing (PHPUnit)
├── frontend/                   # Vue.js Frontend (Vite SPA)
│   ├── dist/                   # Output build production
│   ├── public/                 # Static assets (gambar, logo, dll)
│   └── src/                    # Source code frontend
│       ├── layouts/            # Layout bersama (AdminLayout.vue)
│       ├── router/             # Konfigurasi routing Vue Router
│       ├── services/           # Axios instance & interceptors (api.js)
│       ├── stores/             # Reactive state stores (auth.js, theme.js)
│       ├── views/              # Komponen halaman aplikasi
│       │   ├── HomeView.vue         # Landing page publik
│       │   ├── AbsensiView.vue      # Portal absensi siswa mandiri
│       │   ├── LoginView.vue        # Login petugas admin
│       │   ├── RegisterView.vue     # Registrasi akun petugas baru
│       │   └── admin/               # Halaman dashboard admin
│       │       ├── DashboardView.vue   # Grafik & bento stats harian
│       │       ├── DataSiswaView.vue   # Tabel data siswa & filter
│       │       ├── TambahSiswaView.vue # Formulir tambah siswa manual
│       │       ├── ImportSiswaView.vue # Upload file CSV siswa
│       │       └── AbsensiSiswaView.vue# Rekapitulasi absensi & export
│       ├── App.vue             # Root component Vue
│       ├── main.js             # Entry point inisialisasi aplikasi
│       └── style.css           # Global CSS variables & Base layer styling
├── e_solat_thp.sql             # MySQL database dump (Skema & data awal)
└── README.md                   # File dokumentasi utama ini
```

---

## 📋 Langkah Instalasi & Penggunaan Lokal

### Prasyarat System
* **PHP 8.3+** dengan ekstensi PDO MySQL aktif
* **Composer** (PHP Package Manager)
* **Node.js 18+** & **npm**
* **MySQL** atau **MariaDB** database server

---

### 1. Clone & Persiapan Database
1. Clone repository ke mesin lokal Anda:
   ```bash
   git clone https://github.com/roidpalih-coder/e-Solat-THP.git
   cd e-Solat-THP
   ```
2. Buat database baru di MySQL dengan nama `e_solat_THP`:
   ```sql
   CREATE DATABASE e_solat_THP;
   ```
3. Import file dump SQL (`e_solat_thp.sql`) ke database baru Anda:
   ```bash
   mysql -u root -p e_solat_THP < e_solat_thp.sql
   ```

---

### 2. Setup Backend (Laravel)
1. Pindah ke direktori backend:
   ```bash
   cd backend
   ```
2. Install dependency PHP:
   ```bash
   composer install
   ```
3. Salin konfigrasi env:
   ```bash
   cp .env.example .env
   ```
4. Sesuaikan konfigurasi database Anda di `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=e_solat_THP
   DB_USERNAME=root
   DB_PASSWORD=
   ```
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Jalankan migrasi database & seed data jika ingin menyegarkan database:
   ```bash
   php artisan migrate:fresh --seed
   ```
7. Jalankan server Laravel lokal:
   ```bash
   php artisan serve
   ```
   *Secara default API backend berjalan di `http://127.0.0.1:8000`.*

---

### 3. Setup Frontend (Vue.js)
1. Buka terminal baru dan masuk ke direktori frontend:
   ```bash
   cd frontend
   ```
2. Install dependensi Node.js:
   ```bash
   npm install
   ```
3. Jalankan Vite development server:
   ```bash
   npm run dev
   ```
   *Secara default frontend berjalan di `http://localhost:5173`. Request ke `/api` secara otomatis diproxy ke backend `http://localhost:8000`.*

---

## ⚙️ Environment Variables

### Backend (`backend/.env`)
Berikut adalah variabel lingkungan utama yang dikonfigurasi di Laravel backend:

```env
APP_NAME=e-Solat_THP
APP_ENV=local
APP_KEY=base64:... # Generate otomatis menggunakan php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_solat_THP
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120

CACHE_STORE=database
QUEUE_CONNECTION=database
```

*(Catatan: Frontend tidak memerlukan `.env` karena menggunakan server-side proxy dari konfigurasi Vite (`vite.config.js`) untuk rute `/api`.)*

---

## 🎨 Theme & Styling System

Sistem tema diimplementasikan menggunakan kombinasi **CSS Custom Properties** (Variables) dan kelas deteksi **TailwindCSS**:

1. **Definisi CSS Variables**: Diatur dalam file [style.css](file:///c:/aud/e-Solat%20THP/frontend/src/style.css) di bawah `@layer base`:
   * `:root` (Light Mode): Menggunakan warna berbasis abu-abu terang dan biru cerah.
   * `.dark` (Dark Mode): Menggunakan warna berbasis slate gelap (`#0f172a` / `#1e293b`).
2. **Integrasi Tailwind**: File [tailwind.config.js](file:///c:/aud/e-Solat%20THP/frontend/tailwind.config.js) membaca variabel ini (misalnya `"surface": "var(--color-surface)"`).
3. **Penyimpanan State**: State `isDark` disimpan secara global dalam store reaktif [theme.js](file:///c:/aud/e-Solat%20THP/frontend/src/stores/theme.js) dan dipersistenkan di `localStorage` browser.
4. **Anti-Flicker Script**: Diletakkan secara inline pada `<head>` di [index.html](file:///c:/aud/e-Solat%20THP/frontend/index.html) sehingga browser mendeteksi dan menerapkan kelas `.dark` sebelum DOM pertama kali digambar.

---

## 📈 Grafik & Dashboard

### Tampilan Grafik Mingguan
Grafik didesain secara custom menggunakan elemen CSS HTML flexbox yang dinamis untuk responsivitas maksimal di desktop maupun mobile:
* **Persistent Value**: Menampilkan jumlah kehadiran langsung di atas setiap batang grafik hari Senin - Jumat (`col.total`).
* **Interactive Hover Effect**: Saat kursor diarahkan ke batang grafik, indikator nilai di atasnya akan membesar (*scale-up*), berganti warna menjadi biru primer, dan merubah warna tulisan menjadi putih secara halus menggunakan transisi CSS.

---

## 🌐 Integrasi API

Integrasi API dikelola secara terpusat di [api.js](file:///c:/aud/e-Solat%20THP/frontend/src/services/api.js):
* **Request Interceptor**: Secara otomatis menyisipkan token autentikasi (`Bearer token`) yang tersimpan di `localStorage` ke dalam header HTTP untuk endpoint yang dilindungi.
* **Response Interceptor**: Menangkap error status HTTP `401 Unauthorized` (misal ketika token kadaluarsa), secara otomatis menghapus token dari penyimpanan lokal, dan mengarahkan pengguna kembali ke halaman login.

---

## 📸 Screenshots

Berikut adalah tata letak gambar antarmuka sistem (silakan tambahkan gambar screenshot ke direktori `docs/images/`):

* **Landing Page (Publik)**
  * *Placeholder: `docs/images/home.png`*
* **Portal Absensi NIS (Publik)**
  * *Placeholder: `docs/images/absensi_siswa.png`*
* **Login Petugas (Autentikasi)**
  * *Placeholder: `docs/images/login.png`*
* **Dashboard Summary (Panel Admin)**
  * *Placeholder: `docs/images/dashboard.png`*

---

## 🏗️ Build & Deployment untuk Production

### 1. Build Frontend
Lakukan kompilasi file aset JavaScript dan CSS Vue menjadi file statis siap produksi:
```bash
cd frontend
npm run build
```
File hasil build akan berada di folder `frontend/dist`. Tempatkan isi folder ini ke dalam server web statis atau layani melalui server web backend.

### 2. Optimasi Backend
Lakukan optimasi performa backend Laravel di lingkungan produksi:
```bash
cd backend
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Rekomendasi Deployment
* **Web Server**: Menggunakan **Nginx** sebagai reverse proxy untuk melayani frontend statis dari `frontend/dist` dan meneruskan rute `/api` ke PHP-FPM backend.
* **Keamanan**: Aktifkan **SSL (HTTPS)** (misalnya melalui Let's Encrypt) demi keamanan transmisi token autentikasi.

---

## 🤝 Kontribusi

1. Fork repository ini.
2. Buat branch fitur baru (`git checkout -b feature/FiturBaru`).
3. Lakukan commit perubahan Anda (`git commit -m 'Menambahkan FiturBaru'`).
4. Push ke branch tersebut (`git push origin feature/FiturBaru`).
5. Buka Pull Request di repository utama.

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah **MIT License** - lihat file [LICENSE](LICENSE) untuk detail.

---

## 👤 Author
* **ROIDPALIH** - *The Serene Architect*
* GitHub: [@roidpalih-coder](https://github.com/roidpalih-coder)
