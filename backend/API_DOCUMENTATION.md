# E-SOLAT THP API Documentation

Backend system untuk E-Solat THP (E-Sistem Pencatatan Kehadiran) menggunakan Laravel.

## Setup

### 1. Install Dependencies

```bash
composer install
```

### 2. Setup Database

```bash
php artisan migrate
```

### 3. Generate App Key (jika belum)

```bash
php artisan key:generate
```

### 4. Run Server

```bash
php artisan serve
```

---

## API Endpoints

### Base URL

```
http://localhost:8000/api
```

---

## 1. KELAS (Kelas)

### Get All Kelas

```
GET /api/kelas
```

**Response:**

```json
{
  "status": "success",
  "message": "Data kelas berhasil diambil",
  "data": [
    {
      "id_kelas": 1,
      "nama_kelas": "XII A",
      "created_at": "2026-04-11T10:30:00.000000Z",
      "updated_at": "2026-04-11T10:30:00.000000Z",
      "siswa": [...]
    }
  ]
}
```

### Create Kelas

```
POST /api/kelas
Content-Type: application/json

{
  "nama_kelas": "XII A"
}
```

**Response:** (201 Created)

```json
{
    "status": "success",
    "message": "Kelas berhasil ditambahkan",
    "data": {
        "id_kelas": 1,
        "nama_kelas": "XII A",
        "created_at": "2026-04-11T10:30:00.000000Z",
        "updated_at": "2026-04-11T10:30:00.000000Z"
    }
}
```

### Get Single Kelas

```
GET /api/kelas/{id_kelas}
```

### Update Kelas

```
PUT /api/kelas/{id_kelas}
Content-Type: application/json

{
  "nama_kelas": "XII B"
}
```

### Delete Kelas

```
DELETE /api/kelas/{id_kelas}
```

---

## 2. SISWA (Student)

### Get All Siswa

```
GET /api/siswa
```

**Response:**

```json
{
  "status": "success",
  "message": "Data siswa berhasil diambil",
  "data": [
    {
      "nis": "001",
      "nama_siswa": "Ahmad",
      "jenis_kelamin": "L",
      "id_kelas": 1,
      "created_at": "2026-04-11T10:30:00.000000Z",
      "updated_at": "2026-04-11T10:30:00.000000Z",
      "kelas": {...},
      "absensi": [...]
    }
  ]
}
```

### Create Siswa

```
POST /api/siswa
Content-Type: application/json

{
  "nis": "001",
  "nama_siswa": "Ahmad",
  "jenis_kelamin": "L",
  "id_kelas": 1
}
```

**Note:** `jenis_kelamin` harus `L` (Laki-laki) atau `P` (Perempuan)

### Get Single Siswa

```
GET /api/siswa/{nis}
```

### Update Siswa

```
PUT /api/siswa/{nis}
Content-Type: application/json

{
  "nama_siswa": "Ahmad Wijaya",
  "jenis_kelamin": "L",
  "id_kelas": 2
}
```

### Delete Siswa

```
DELETE /api/siswa/{nis}
```

---

## 3. PETUGAS (Officer)

### Get All Petugas

```
GET /api/petugas
```

**Response:**

```json
{
  "status": "success",
  "message": "Data petugas berhasil diambil",
  "data": [
    {
      "id_petugas": 1,
      "username": "admin1",
      "created_at": "2026-04-11T10:30:00.000000Z",
      "updated_at": "2026-04-11T10:30:00.000000Z",
      "absensi": [...]
    }
  ]
}
```

**Note:** Password tidak ditampilkan dalam response (hidden field)

### Create Petugas

```
POST /api/petugas
Content-Type: application/json

{
  "username": "admin1",
  "password": "password123"
}
```

**Note:** Password akan di-hash otomatis menggunakan bcrypt

### Get Single Petugas

```
GET /api/petugas/{id_petugas}
```

### Update Petugas

```
PUT /api/petugas/{id_petugas}
Content-Type: application/json

{
  "username": "admin2",
  "password": "newpassword123"
}
```

**Note:** Password opsional, jika tidak dikirim password tidak akan diubah

### Delete Petugas

```
DELETE /api/petugas/{id_petugas}
```

---

## 4. ABSENSI (Attendance)

### Get All Absensi

```
GET /api/absensi
```

**Response:**

```json
{
  "status": "success",
  "message": "Data absensi berhasil diambil",
  "data": [
    {
      "id_absensi": 1,
      "nis": "001",
      "tanggal": "2026-04-11",
      "waktu_absen": "07:30:00",
      "id_petugas": 1,
      "created_at": "2026-04-11T10:30:00.000000Z",
      "updated_at": "2026-04-11T10:30:00.000000Z",
      "siswa": {...},
      "petugas": {...}
    }
  ]
}
```

### Create Absensi

```
POST /api/absensi
Content-Type: application/json

{
  "nis": "001",
  "tanggal": "2026-04-11",
  "waktu_absen": "07:30:00",
  "id_petugas": 1
}
```

**Format:**

-   `tanggal`: format YYYY-MM-DD (e.g., 2026-04-11)
-   `waktu_absen`: format HH:MM:SS (e.g., 07:30:00)

### Get Single Absensi

```
GET /api/absensi/{id_absensi}
```

### Update Absensi

```
PUT /api/absensi/{id_absensi}
Content-Type: application/json

{
  "nis": "001",
  "tanggal": "2026-04-11",
  "waktu_absen": "08:00:00",
  "id_petugas": 1
}
```

### Delete Absensi

```
DELETE /api/absensi/{id_absensi}
```

### Get Absensi by Date

```
GET /api/absensi/by-date/{tanggal}
```

**Example:**

```
GET /api/absensi/by-date/2026-04-11
```

### Get Absensi by Siswa

```
GET /api/absensi/siswa/{nis}
```

**Example:**

```
GET /api/absensi/siswa/001
```

---

## Error Handling

### 404 Not Found

```json
{
    "message": "No query results found for model [App\\Models\\Siswa] 001"
}
```

### 422 Validation Error

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "nama_kelas": ["The nama_kelas field is required."]
    }
}
```

### 500 Server Error

```json
{
    "message": "Server Error"
}
```

---

## Database Schema

### Tabel: kelas

| Column     | Type         | Notes              |
| ---------- | ------------ | ------------------ |
| id_kelas   | bigint       | PK, Auto Increment |
| nama_kelas | varchar(255) |                    |
| created_at | timestamp    |                    |
| updated_at | timestamp    |                    |

### Tabel: siswa

| Column        | Type          | Notes       |
| ------------- | ------------- | ----------- |
| nis           | varchar(255)  | PK          |
| nama_siswa    | varchar(255)  |             |
| jenis_kelamin | enum('L','P') |             |
| id_kelas      | bigint        | FK to kelas |
| created_at    | timestamp     |             |
| updated_at    | timestamp     |             |

### Tabel: petugas

| Column     | Type         | Notes              |
| ---------- | ------------ | ------------------ |
| id_petugas | bigint       | PK, Auto Increment |
| username   | varchar(255) | UNIQUE             |
| password   | varchar(255) | Hashed             |
| created_at | timestamp    |                    |
| updated_at | timestamp    |                    |

### Tabel: absensi

| Column      | Type         | Notes              |
| ----------- | ------------ | ------------------ |
| id_absensi  | bigint       | PK, Auto Increment |
| nis         | varchar(255) | FK to siswa        |
| tanggal     | date         |                    |
| waktu_absen | time         |                    |
| id_petugas  | bigint       | FK to petugas      |
| created_at  | timestamp    |                    |
| updated_at  | timestamp    |                    |

---

## Relasi Model

```
Kelas (1) ──────→ (Many) Siswa
Siswa (1) ──────→ (Many) Absensi
Petugas (1) ────→ (Many) Absensi
```

---

## Testing dengan Postman

### 1. Create Kelas

-   Method: `POST`
-   URL: `http://localhost:8000/api/kelas`
-   Body (JSON):
    ```json
    {
        "nama_kelas": "XII A"
    }
    ```

### 2. Get All Kelas

-   Method: `GET`
-   URL: `http://localhost:8000/api/kelas`

### 3. Create Siswa

-   Method: `POST`
-   URL: `http://localhost:8000/api/siswa`
-   Body (JSON):
    ```json
    {
        "nis": "001",
        "nama_siswa": "Ahmad",
        "jenis_kelamin": "L",
        "id_kelas": 1
    }
    ```

### 4. Create Petugas

-   Method: `POST`
-   URL: `http://localhost:8000/api/petugas`
-   Body (JSON):
    ```json
    {
        "username": "admin1",
        "password": "password123"
    }
    ```

### 5. Create Absensi

-   Method: `POST`
-   URL: `http://localhost:8000/api/absensi`
-   Body (JSON):
    ```json
    {
        "nis": "001",
        "tanggal": "2026-04-11",
        "waktu_absen": "07:30:00",
        "id_petugas": 1
    }
    ```

---

## Notes

-   Semua response menggunakan JSON format
-   Password di-hash dengan bcrypt
-   Soft delete belum diimplementasikan (hard delete)
-   Validation dilakukan di Controller
-   Foreign key constraints dengan cascade delete
