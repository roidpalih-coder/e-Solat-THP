# Tips & Advanced Queries - E-SOLAT THP Backend

Panduan advanced tips dan custom queries untuk backend E-SOLAT THP.

---

## Custom Query Examples

### 1. Get Absensi Statistics untuk Siswa

```php
// Di controller atau custom class
$siswaStats = Siswa::with('absensi')
    ->get()
    ->map(function ($siswa) {
        return [
            'nis' => $siswa->nis,
            'nama' => $siswa->nama_siswa,
            'total_absensi' => $siswa->absensi()->count(),
            'pada_tanggal' => $siswa->absensi()->pluck('tanggal'),
        ];
    });
```

### 2. Get Siswa yang Belum Absen pada Tanggal Tertentu

```php
$tanggal = '2026-04-11';
$siswaAktif = Siswa::where('id_kelas', $id_kelas)->get();
$siswaTerabsensi = Absensi::whereDate('tanggal', $tanggal)
    ->pluck('nis');

$siswaYangBelumAbsen = $siswaAktif->whereNotIn('nis', $siswaTerabsensi);
```

### 3. Get Absensi per Kelas pada Tanggal Tertentu

```php
$absensiPerKelas = Kelas::with('siswa')
    ->get()
    ->map(function ($kelas) use ($tanggal) {
        $siswaKelas = $kelas->siswa;
        $absensiKelas = Absensi::whereDate('tanggal', $tanggal)
            ->whereIn('nis', $siswaKelas->pluck('nis'))
            ->count();

        return [
            'kelas' => $kelas->nama_kelas,
            'total_siswa' => $siswaKelas->count(),
            'hadir' => $absensiKelas,
            'persentase_kehadiran' => $siswaKelas->count() > 0
                ? round(($absensiKelas / $siswaKelas->count()) * 100, 2) . '%'
                : '0%'
        ];
    });
```

### 4. Get Rekap Absensi Harian

```php
public function getRekapAbsensiHarian($tanggal)
{
    return Absensi::whereDate('tanggal', $tanggal)
        ->with('siswa.kelas', 'petugas')
        ->orderBy('waktu_absen')
        ->get()
        ->groupBy('siswa.id_kelas')
        ->map(function ($group) {
            return [
                'kelas' => $group->first()->siswa->kelas->nama_kelas,
                'daftar_absensi' => $group->map(function ($absensi) {
                    return [
                        'nis' => $absensi->nis,
                        'nama' => $absensi->siswa->nama_siswa,
                        'waktu' => $absensi->waktu_absen,
                        'petugas' => $absensi->petugas->username,
                    ];
                })
            ];
        });
}
```

### 5. Get Siswa dengan Absensi Tertinggi/Terendah

```php
// Absensi Tertinggi
$siswaAbsensiTertinggi = Siswa::withCount('absensi')
    ->orderByDesc('absensi_count')
    ->limit(10)
    ->get();

// Absensi Terendah
$siswaAbsensiTerendah = Siswa::withCount('absensi')
    ->orderBy('absensi_count')
    ->limit(10)
    ->get();
```

---

## API Response Helpers

### Custom Response Format

```php
// Buat helper class di app/Helpers/ApiResponse.php
namespace App\Helpers;

class ApiResponse
{
    public static function success($data, $message = 'Success', $statusCode = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'timestamp' => now()
        ], $statusCode);
    }

    public static function error($message = 'Error', $statusCode = 400, $errors = null)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
            'timestamp' => now()
        ], $statusCode);
    }

    public static function paginated($query, $perPage = 15)
    {
        return $query->paginate($perPage);
    }
}
```

### Usage dalam Controller

```php
use App\Helpers\ApiResponse;

// Success response
return ApiResponse::success($data, 'Data retrieved successfully');

// Error response
return ApiResponse::error('Resource not found', 404);
```

---

## Validation Rules

### Custom Validation Request

```php
// Run: php artisan make:request CreateSiswaRequest

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSiswaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nis' => 'required|string|unique:siswa,nis',
            'nama_siswa' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'id_kelas' => 'required|integer|exists:kelas,id_kelas',
        ];
    }

    public function messages()
    {
        return [
            'nis.unique' => 'NIS sudah terdaftar',
            'id_kelas.exists' => 'Kelas tidak ditemukan',
        ];
    }
}
```

---

## Search & Filter Features

### Tambahkan Search di Controller

```php
public function search(Request $request)
{
    $query = Siswa::query();

    if ($request->has('nama_siswa')) {
        $query->where('nama_siswa', 'like', '%' . $request->nama_siswa . '%');
    }

    if ($request->has('id_kelas')) {
        $query->where('id_kelas', $request->id_kelas);
    }

    if ($request->has('jenis_kelamin')) {
        $query->where('jenis_kelamin', $request->jenis_kelamin);
    }

    return response()->json([
        'status' => 'success',
        'data' => $query->with('kelas')->get()
    ]);
}
```

### Usage

```
GET /api/siswa/search?nama_siswa=Ahmad&jenis_kelamin=L&id_kelas=1
```

---

## Date Range Queries

### Get Absensi dalam Range Tanggal

```php
public function getAbsensiByDateRange(Request $request)
{
    $validated = $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
    ]);

    $absensi = Absensi::whereBetween('tanggal', [$validated['start_date'], $validated['end_date']])
        ->with('siswa', 'petugas')
        ->orderBy('tanggal')
        ->get();

    return response()->json([
        'status' => 'success',
        'data' => $absensi
    ]);
}
```

### Route

```php
Route::get('/absensi/range', [AbsensiController::class, 'getAbsensiByDateRange']);
```

---

## Relationship Eager Loading

### Hindari N+1 Query Problem

```php
// ❌ BAD - Causes N+1 queries
foreach ($siswa as $s) {
    echo $s->kelas->nama_kelas; // Query for each loop
}

// ✅ GOOD - Use eager loading
$siswa = Siswa::with('kelas')->get();
foreach ($siswa as $s) {
    echo $s->kelas->nama_kelas; // No additional queries
}

// ✅ GOOD - Multiple relationships
$siswa = Siswa::with('kelas', 'absensi', 'absensi.petugas')->get();
```

---

## Export/Import Features (Optional)

### Export Absensi ke CSV

```php
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

public function exportAbsensi($tanggal)
{
    $absensi = Absensi::whereDate('tanggal', $tanggal)
        ->with('siswa', 'petugas')
        ->get();

    $csv = fopen('php://temp', 'r+');
    fputcsv($csv, ['NIS', 'Nama Siswa', 'Tanggal', 'Waktu Absen', 'Petugas']);

    foreach ($absensi as $item) {
        fputcsv($csv, [
            $item->nis,
            $item->siswa->nama_siswa,
            $item->tanggal,
            $item->waktu_absen,
            $item->petugas->username,
        ]);
    }

    rewind($csv);
    $content = stream_get_contents($csv);
    fclose($csv);

    return response($content, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="absensi_' . $tanggal . '.csv"',
    ]);
}
```

---

## Caching Strategy

### Cache Query Results

```php
use Illuminate\Support\Facades\Cache;

public function index()
{
    // Cache untuk 1 jam (3600 detik)
    $kelas = Cache::remember('all_kelas', 3600, function () {
        return Kelas::with('siswa')->get();
    });

    return response()->json([
        'status' => 'success',
        'data' => $kelas
    ]);
}

// Invalidate cache setelah update
public function update(Request $request, $id_kelas)
{
    // ... update logic ...

    Cache::forget('all_kelas');

    return response()->json([
        'status' => 'success',
        'message' => 'Kelas updated'
    ]);
}
```

---

## Rate Limiting (Optional)

### Setup Rate Limiting

```php
// Di routes/api.php
Route::middleware('throttle:60,1')->group(function () {
    Route::apiResource('kelas', KelasController::class);
    Route::apiResource('siswa', SiswaController::class);
    Route::apiResource('petugas', PetugasController::class);
    Route::apiResource('absensi', AbsensiController::class);
});

// 60 requests per 1 minute
```

---

## Logging

### Log API Requests

```php
// Di app/Http/Middleware/LogApiRequests.php
namespace App\Http\Middleware;

use Illuminate\Support\Facades\Log;

class LogApiRequests
{
    public function handle($request, $next)
    {
        Log::info('API Request', [
            'method' => $request->method(),
            'path' => $request->path(),
            'user_ip' => $request->ip(),
            'timestamp' => now()
        ]);

        return $next($request);
    }
}
```

---

## Best Practices

1. **Always use relationships** - Hindari N+1 queries dengan eager loading
2. **Validate input** - Gunakan Form Request atau validate() method
3. **Handle errors** - Return proper HTTP status codes
4. **Use transactions** - Untuk operasi yang melibatkan multiple tables
5. **Cache wisely** - Cache expensive queries tapi invalidate setelah update
6. **Log everything** - Untuk debugging dan monitoring
7. **Use pagination** - Untuk large datasets
8. **API versioning** - Plan untuk v2 di routes/api/v2.php

---

## Common Errors & Solutions

| Error                      | Penyebab                  | Solusi                               |
| -------------------------- | ------------------------- | ------------------------------------ |
| 500 Internal Server Error  | Typo atau logic error     | Check storage/logs/laravel.log       |
| 422 Unprocessable Entity   | Validation error          | Check error messages di response     |
| 404 Not Found              | Resource tidak ada        | Verifikasi ID/NIS yang digunakan     |
| PDOException               | Database connection error | Check DB credentials di .env         |
| "Call to undefined method" | Method tidak ada di model | Check model relationship definitions |

---

## Resources

-   Laravel Docs: https://laravel.com/docs/11
-   Eloquent ORM: https://laravel.com/docs/11/eloquent
-   API Resource: https://laravel.com/docs/11/eloquent-resources
