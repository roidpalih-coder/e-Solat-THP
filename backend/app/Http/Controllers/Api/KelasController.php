<?php

namespace App\Http\Controllers\Api;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class KelasController extends Controller
{
    public function index(): JsonResponse
    {
        $kelas = Kelas::with('siswa')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Data kelas berhasil diambil',
            'data' => $kelas
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
        ]);

        $kelas = Kelas::create($validated);
        return response()->json([
            'status' => 'success',
            'message' => 'Kelas berhasil ditambahkan',
            'data' => $kelas
        ], 201);
    }

    public function show($id_kelas): JsonResponse
    {
        $kelas = Kelas::with('siswa')->findOrFail($id_kelas);
        return response()->json([
            'status' => 'success',
            'data' => $kelas
        ]);
    }

    public function update(Request $request, $id_kelas): JsonResponse
    {
        $kelas = Kelas::findOrFail($id_kelas);

        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
        ]);

        $kelas->update($validated);
        return response()->json([
            'status' => 'success',
            'message' => 'Kelas berhasil diupdate',
            'data' => $kelas
        ]);
    }

    public function destroy($id_kelas): JsonResponse
    {
        $kelas = Kelas::findOrFail($id_kelas);
        $kelas->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kelas berhasil dihapus'
        ]);
    }
}
