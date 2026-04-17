<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\PetugasController;
use App\Http\Controllers\Api\AbsensiController;

// Route Public (Bisa diakses tanpa login)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Absensi publik untuk siswa (tanpa token)
Route::post('/absensi/public', [AbsensiController::class, 'storePublic']);

// Route Protected (Harus Login dengan Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    // Auth Routes
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Kelas Routes
    Route::apiResource('kelas', KelasController::class);

    // Siswa Routes
    Route::post('/siswa/import', [SiswaController::class, 'importBulk']);
    Route::apiResource('siswa', SiswaController::class);

    // Petugas Routes
    Route::apiResource('petugas', PetugasController::class);

    // Absensi Routes
    Route::prefix('absensi')->group(function () {
        // Static routes MUST come before dynamic routes
        Route::get('/by-date/{tanggal}', [AbsensiController::class, 'getByDate']);
        Route::get('/siswa/{nis}', [AbsensiController::class, 'getBySiswa']);
    });
    Route::apiResource('absensi', AbsensiController::class)->except(['update']);
});
