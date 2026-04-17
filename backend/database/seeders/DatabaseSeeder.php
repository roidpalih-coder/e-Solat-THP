<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Petugas;
use App\Models\Absensi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Kelas
        $kelas1 = Kelas::create(['nama_kelas' => 'XII A']);
        $kelas2 = Kelas::create(['nama_kelas' => 'XII B']);
        $kelas3 = Kelas::create(['nama_kelas' => 'XII C']);

        // Create Siswa
        $siswa1 = Siswa::create([
            'nis' => '001',
            'nama_siswa' => 'Ahmad Wijaya',
            'jenis_kelamin' => 'L',
            'id_kelas' => $kelas1->id_kelas,
        ]);

        $siswa2 = Siswa::create([
            'nis' => '002',
            'nama_siswa' => 'Siti Nurhaliza',
            'jenis_kelamin' => 'P',
            'id_kelas' => $kelas1->id_kelas,
        ]);

        $siswa3 = Siswa::create([
            'nis' => '003',
            'nama_siswa' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'id_kelas' => $kelas2->id_kelas,
        ]);

        $siswa4 = Siswa::create([
            'nis' => '004',
            'nama_siswa' => 'Rina Suryani',
            'jenis_kelamin' => 'P',
            'id_kelas' => $kelas2->id_kelas,
        ]);

        // Create Petugas
        $petugas1 = Petugas::create([
            'username' => 'admin1',
            'password' => Hash::make('password123'),
        ]);

        $petugas2 = Petugas::create([
            'username' => 'admin2',
            'password' => Hash::make('password123'),
        ]);

        // Create Absensi
        Absensi::create([
            'nis' => $siswa1->nis,
            'tanggal' => '2026-04-11',
            'waktu_absen' => '07:30:00',
            'id_petugas' => $petugas1->id_petugas,
        ]);

        Absensi::create([
            'nis' => $siswa2->nis,
            'tanggal' => '2026-04-11',
            'waktu_absen' => '07:25:00',
            'id_petugas' => $petugas1->id_petugas,
        ]);

        Absensi::create([
            'nis' => $siswa3->nis,
            'tanggal' => '2026-04-11',
            'waktu_absen' => '07:35:00',
            'id_petugas' => $petugas2->id_petugas,
        ]);

        Absensi::create([
            'nis' => $siswa4->nis,
            'tanggal' => '2026-04-11',
            'waktu_absen' => '07:28:00',
            'id_petugas' => $petugas2->id_petugas,
        ]);
    }
}
