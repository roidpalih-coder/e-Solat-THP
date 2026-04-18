<?php

namespace App\Http\Controllers\Api;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AbsensiController extends Controller
{
    public function index(): JsonResponse
    {
        $absensi = Absensi::with('siswa.kelas', 'petugas')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Data absensi berhasil diambil',
            'data' => $absensi
        ]);
    }

    /**
     * Store absensi oleh petugas (protected, butuh token)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nis' => 'required|string|exists:siswa,nis',
        ]);

        $validated['id_petugas']  = $request->user()->id_petugas;
        $validated['tanggal']     = now()->toDateString();
        $validated['waktu_absen'] = now()->format('H:i:s');

        $absensi = Absensi::create($validated);
        return response()->json([
            'status' => 'success',
            'message' => 'Absensi berhasil ditambahkan',
            'data' => $absensi->load('siswa.kelas', 'petugas')
        ], 201);
    }

    /**
     * Absensi publik oleh siswa sendiri (tanpa token)
     */
    public function storePublic(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nis' => 'required|string|exists:siswa,nis',
        ]);

        // Cek apakah siswa sudah absen hari ini
        $sudahAbsen = Absensi::where('nis', $validated['nis'])
            ->whereDate('tanggal', now()->toDateString())
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa sudah melakukan absensi hari ini.',
            ], 422);
        }

        $absensi = Absensi::create([
            'nis'         => $validated['nis'],
            'id_petugas'  => null,
            'tanggal'     => now()->toDateString(),
            'waktu_absen' => now()->format('H:i:s'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Absensi berhasil dicatat',
            'data' => $absensi->load('siswa.kelas')
        ], 201);
    }

    public function show($id_absensi): JsonResponse
    {
        $absensi = Absensi::with('siswa.kelas', 'petugas')->findOrFail($id_absensi);
        return response()->json([
            'status' => 'success',
            'data' => $absensi
        ]);
    }

    public function update(Request $request, $id_absensi): JsonResponse
    {
        $absensi = Absensi::findOrFail($id_absensi);

        $validated = $request->validate([
            'nis' => 'sometimes|string|exists:siswa,nis',
            'tanggal' => 'sometimes|date',
            'waktu_absen' => 'sometimes|date_format:H:i:s',
            'id_petugas' => 'sometimes|integer|exists:petugas,id_petugas',
        ]);

        $absensi->update($validated);
        return response()->json([
            'status' => 'success',
            'message' => 'Absensi berhasil diupdate',
            'data' => $absensi->load('siswa.kelas', 'petugas')
        ]);
    }

    public function destroy($id_absensi): JsonResponse
    {
        $absensi = Absensi::findOrFail($id_absensi);
        $absensi->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Absensi berhasil dihapus'
        ]);
    }

    public function getByDate($tanggal): JsonResponse
    {
        if (!Validator::make(
            ['tanggal' => $tanggal],
            ['tanggal' => 'required|date']
        )->passes()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Format tanggal tidak valid. Gunakan format YYYY-MM-DD',
            ], 422);
        }

        $absensi = Absensi::whereDate('tanggal', $tanggal)
            ->with('siswa.kelas', 'petugas')
            ->orderBy('waktu_absen', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data absensi berdasarkan tanggal berhasil diambil',
            'data' => $absensi
        ]);
    }

    public function getBySiswa($nis): JsonResponse
    {
        $absensi = Absensi::where('nis', $nis)
            ->with('siswa.kelas', 'petugas')
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data absensi siswa berhasil diambil',
            'data' => $absensi
        ]);
    }

    public function getWeeklyStats(): JsonResponse
    {
        $startDate = now()->subDays(6)->toDateString();
        $endDate = now()->toDateString();
        
        $absensi = Absensi::selectRaw('tanggal, count(*) as total')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Weekly stats retrieved',
            'data' => $absensi
        ]);
    }

    public function getPublicStats(): JsonResponse
    {
        $today = now()->toDateString();
        $totalSiswa = \App\Models\Siswa::count();
        $absenHariIni = Absensi::whereDate('tanggal', $today)->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_siswa' => $totalSiswa,
                'absen_hari_ini' => $absenHariIni
            ]
        ]);
    }
}
