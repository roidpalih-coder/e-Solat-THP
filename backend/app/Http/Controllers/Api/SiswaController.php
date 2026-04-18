<?php

namespace App\Http\Controllers\Api;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SiswaController extends Controller
{
    public function index(): JsonResponse
    {
        $siswa = Siswa::with('kelas', 'absensi')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Data siswa berhasil diambil',
            'data' => $siswa
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nis'           => 'required|string|unique:siswa,nis',
            'nama_siswa'    => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'id_kelas'      => 'required|integer|exists:kelas,id_kelas',
        ]);

        $siswa = Siswa::create($validated);
        return response()->json([
            'status' => 'success',
            'message' => 'Siswa berhasil ditambahkan',
            'data' => $siswa->load('kelas')
        ], 201);
    }

    public function show($nis): JsonResponse
    {
        $siswa = Siswa::with('kelas', 'absensi')->findOrFail($nis);
        return response()->json([
            'status' => 'success',
            'data' => $siswa
        ]);
    }

    public function update(Request $request, $nis): JsonResponse
    {
        $siswa = Siswa::findOrFail($nis);

        $validated = $request->validate([
            'nama_siswa'    => 'sometimes|string|max:255',
            'jenis_kelamin' => 'sometimes|in:Laki-laki,Perempuan',
            'id_kelas'      => 'sometimes|integer|exists:kelas,id_kelas',
        ]);

        $siswa->update($validated);
        return response()->json([
            'status' => 'success',
            'message' => 'Siswa berhasil diupdate',
            'data' => $siswa->load('kelas')
        ]);
    }

    public function destroy($nis): JsonResponse
    {
        $siswa = Siswa::findOrFail($nis);
        $siswa->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Siswa berhasil dihapus'
        ]);
    }

    /**
     * Import bulk siswa dari array (CSV/Excel parsed di frontend)
     */
    public function importBulk(Request $request): JsonResponse
    {
        $request->validate([
            'data'                  => 'required|array|min:1',
            'data.*.nis'            => 'required|string',
            'data.*.nama_siswa'     => 'required|string|max:255',
            'data.*.jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'data.*.nama_kelas'     => 'required|string',
            'mode'                  => 'sometimes|in:append,replace',
        ]);

        $results = [];
        $successCount = 0;
        $errorCount = 0;

        foreach ($request->data as $index => $row) {
            // Cari kelas berdasarkan nama
            $kelas = Kelas::where('nama_kelas', $row['nama_kelas'])->first();

            if (!$kelas) {
                // Buat kelas baru jika belum ada
                $kelas = Kelas::create(['nama_kelas' => $row['nama_kelas']]);
            }

            // Cek apakah NIS sudah ada
            $existing = Siswa::where('nis', $row['nis'])->first();

            if ($existing) {
                if ($request->input('mode') === 'replace') {
                    $existing->update([
                        'nama_siswa'    => $row['nama_siswa'],
                        'jenis_kelamin' => $row['jenis_kelamin'],
                        'id_kelas'      => $kelas->id_kelas,
                    ]);
                    $results[] = [
                        'baris' => $index + 2,
                        'nis' => $row['nis'],
                        'status' => 'success',
                        'pesan' => 'Data diperbarui.'
                    ];
                    $successCount++;
                } else {
                    $results[] = [
                        'baris' => $index + 2,
                        'nis' => $row['nis'],
                        'status' => 'skip',
                        'pesan' => 'NIS sudah terdaftar, dilewati.'
                    ];
                    $errorCount++;
                }
                continue;
            }

            try {
                Siswa::create([
                    'nis'           => $row['nis'],
                    'nama_siswa'    => $row['nama_siswa'],
                    'jenis_kelamin' => $row['jenis_kelamin'],
                    'id_kelas'      => $kelas->id_kelas,
                ]);
                $results[] = [
                    'baris' => $index + 2,
                    'nis' => $row['nis'],
                    'status' => 'success',
                    'pesan' => 'Berhasil ditambahkan.'
                ];
                $successCount++;
            } catch (\Exception $e) {
                $results[] = [
                    'baris' => $index + 2,
                    'nis' => $row['nis'],
                    'status' => 'error',
                    'pesan' => $e->getMessage()
                ];
                $errorCount++;
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => "Import selesai: {$successCount} berhasil, {$errorCount} gagal/dilewati.",
            'summary' => ['success' => $successCount, 'error' => $errorCount],
            'results' => $results,
        ]);
    }
}
