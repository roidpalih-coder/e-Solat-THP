# Authentication Guide - E-SOLAT THP API

Backend E-SOLAT THP menggunakan **Laravel Sanctum** untuk API authentication berbasis token.

---

## Setup Sanctum

### 1. Install Sanctum (Jika belum)

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### 2. Publish Sanctum Config

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

---

## How It Works

1. **Public Routes** (tanpa token):

    - `/api/register` - Daftar akun baru
    - `/api/login` - Login & dapatkan token

2. **Protected Routes** (butuh token):
    - Semua routes CRUD: `/api/kelas`, `/api/siswa`, `/api/petugas`, `/api/absensi`
    - `/api/me` - Lihat profile user
    - `/api/logout` - Logout

---

## API Endpoints

### 1. REGISTER (Public)

**Request:**

```http
POST /api/register
Content-Type: application/json

{
  "username": "admin1",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (201):**

```json
{
    "status": "success",
    "message": "Registrasi berhasil",
    "data": {
        "id_petugas": 1,
        "username": "admin1"
    }
}
```

---

### 2. LOGIN (Public)

**Request:**

```http
POST /api/login
Content-Type: application/json

{
  "username": "admin1",
  "password": "password123"
}
```

**Response (200):**

```json
{
    "status": "success",
    "message": "Login berhasil",
    "data": {
        "id_petugas": 1,
        "username": "admin1",
        "token": "1|abc123xyz...token...here",
        "token_type": "Bearer"
    }
}
```

⚠️ **Simpan token ini!** Gunakan di setiap request protected.

---

### 3. GET CURRENT USER (Protected)

**Request:**

```http
GET /api/me
Authorization: Bearer 1|abc123xyz...token...here
```

**Response (200):**

```json
{
    "status": "success",
    "data": {
        "id_petugas": 1,
        "username": "admin1",
        "created_at": "2026-04-11T10:30:00.000000Z",
        "updated_at": "2026-04-11T10:30:00.000000Z"
    }
}
```

---

### 4. LOGOUT (Protected)

**Request:**

```http
POST /api/logout
Authorization: Bearer 1|abc123xyz...token...here
```

**Response (200):**

```json
{
    "status": "success",
    "message": "Logout berhasil"
}
```

---

## Protected Routes (Semua memerlukan Token)

### Kelas (CRUD)

```
GET    /api/kelas              # Get all
POST   /api/kelas              # Create
GET    /api/kelas/{id}         # Show
PUT    /api/kelas/{id}         # Update
DELETE /api/kelas/{id}         # Delete
```

### Siswa (CRUD)

```
GET    /api/siswa              # Get all
POST   /api/siswa              # Create
GET    /api/siswa/{nis}        # Show
PUT    /api/siswa/{nis}        # Update
DELETE /api/siswa/{nis}        # Delete
```

### Petugas (CRUD)

```
GET    /api/petugas            # Get all
POST   /api/petugas            # Create
GET    /api/petugas/{id}       # Show
PUT    /api/petugas/{id}       # Update
DELETE /api/petugas/{id}       # Delete
```

### Absensi (CRUD + Special)

```
GET    /api/absensi            # Get all
POST   /api/absensi            # Create
GET    /api/absensi/{id}       # Show
PUT    /api/absensi/{id}       # Update
DELETE /api/absensi/{id}       # Delete

# Special endpoints
GET    /api/absensi/by-date/{tanggal}   # Filter by date
GET    /api/absensi/siswa/{nis}         # Filter by siswa
```

---

## Testing di Postman

### Step 1: Register

1. Buka Postman
2. **Method:** POST
3. **URL:** `http://localhost:8000/api/register`
4. **Headers:**
    ```
    Content-Type: application/json
    ```
5. **Body (raw JSON):**
    ```json
    {
        "username": "admin1",
        "password": "password123",
        "password_confirmation": "password123"
    }
    ```
6. Klik **Send**

### Step 2: Login

1. **Method:** POST
2. **URL:** `http://localhost:8000/api/login`
3. **Headers:**
    ```
    Content-Type: application/json
    ```
4. **Body (raw JSON):**
    ```json
    {
        "username": "admin1",
        "password": "password123"
    }
    ```
5. Klik **Send**
6. **Copy token** dari response (field `data.token`)

### Step 3: Setup Bearer Token (Postman)

**Option A: Manual di setiap request**

-   Tab **Headers**
-   Tambah:
    ```
    Authorization: Bearer [paste_token_here]
    ```

**Option B: Setup di Collection (Recommended)**

1. Pilih collection
2. Tab **Authorization**
3. **Type:** Bearer Token
4. **Token:** [paste_token_here]
5. Semua request di collection otomatis pakai token ini

### Step 4: Test Protected Routes

Sekarang semua routes bisa diakses dengan token, contoh:

**Get All Kelas:**

```
GET http://localhost:8000/api/kelas
Headers: Authorization: Bearer [token]
```

**Create Kelas:**

```
POST http://localhost:8000/api/kelas
Headers: Authorization: Bearer [token]
Body (raw JSON):
{
  "nama_kelas": "XII A"
}
```

---

## Common Errors

### 401 Unauthorized

```json
{
    "message": "Unauthenticated."
}
```

**Solusi:**

-   Token tidak dikirim atau expired
-   Gunakan token yang benar di Authorization header

### 422 Validation Error (Register/Login)

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "username": ["The username field is required."]
    }
}
```

**Solusi:** Validasi input sesuai requirements

### 400 Invalid Credentials (Login)

```json
{
    "status": "error",
    "message": "Username atau password salah"
}
```

**Solusi:** Cek username dan password yang benar

---

## Postman Environment Setup (Optional)

Untuk kemudahan testing:

1. **Create Environment**

    - Klik gear icon → Manage Environments
    - New → `E-SOLAT THP`

2. **Add Variables**

    ```
    base_url: http://localhost:8000/api
    token: (nanti diisi setelah login)
    ```

3. **Update Variables Script (di Request > Tests)**

    ```javascript
    if (pm.response.code === 200) {
        var jsonData = pm.response.json();
        if (jsonData.data.token) {
            pm.environment.set("token", jsonData.data.token);
        }
    }
    ```

4. **Gunakan di Request**
    - URL: `{{base_url}}/kelas`
    - Authorization: `Bearer {{token}}`

---

## Important Notes

-   **Token** valid selama session belum di-logout
-   **Password** harus di-confirm saat register
-   **Logout** menghapus token, harus login lagi
-   Semua endpoint mengembalikan **JSON response**
-   **HTTPS recommended** untuk production

---

## Flow Chart

```
1. Register (POST /api/register)
   ↓
2. Login (POST /api/login) → Dapatkan TOKEN
   ↓
3. Gunakan TOKEN di Authorization header
   ↓
4. Access Protected Routes (Kelas, Siswa, Petugas, Absensi)
   ↓
5. Logout (POST /api/logout) → Token dihapus
```

---

## Tips & Tricks

### Test dengan cURL

```bash
# Register
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"username":"admin1","password":"password123","password_confirmation":"password123"}'

# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin1","password":"password123"}'

# Get Kelas (dengan token)
curl -H "Authorization: Bearer YOUR_TOKEN" \
  http://localhost:8000/api/kelas
```

### Refresh Token

Jika ingin implement token refresh (optional):

```php
// Add ke AuthController
public function refresh(Request $request)
{
    $request->user()->tokens()->delete();
    $token = $request->user()->createToken('auth_token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'token_type' => 'Bearer',
    ]);
}
```

---

## File Structure

```
app/Http/Controllers/Api/
├── AuthController.php       ← Authentication
├── KelasController.php
├── SiswaController.php
├── PetugasController.php
└── AbsensiController.php

app/Models/
├── Petugas.php              ← With HasApiTokens trait
├── Kelas.php
├── Siswa.php
└── Absensi.php

routes/
└── api.php                  ← Public & Protected routes
```

---

Sekarang siap testing! 🚀
