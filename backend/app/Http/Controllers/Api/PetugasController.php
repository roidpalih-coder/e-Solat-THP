<?php

namespace App\Http\Controllers\Api;

use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    public function index(): JsonResponse
    {
        $petugas = Petugas::with('absensi')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Data petugas berhasil diambil',
            'data' => $petugas
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_petugas' => 'required|string|max:255',
            'username'     => 'required|string|unique:petugas,username',
            'password'     => 'required|string|min:6',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $petugas = Petugas::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Petugas berhasil ditambahkan',
            'data' => $petugas
        ], 201);
    }

    public function show($id_petugas): JsonResponse
    {
        $petugas = Petugas::with('absensi')->findOrFail($id_petugas);
        return response()->json([
            'status' => 'success',
            'data' => $petugas
        ]);
    }

    public function update(Request $request, $id_petugas): JsonResponse
    {
        $petugas = Petugas::findOrFail($id_petugas);

        $validated = $request->validate([
            'nama_petugas' => 'sometimes|string|max:255',
            'username'     => 'sometimes|string|unique:petugas,username,' . $id_petugas . ',id_petugas',
            'password'     => 'sometimes|string|min:6',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $petugas->update($validated);
        return response()->json([
            'status' => 'success',
            'message' => 'Petugas berhasil diupdate',
            'data' => $petugas
        ]);
    }

    public function destroy($id_petugas): JsonResponse
    {
        $petugas = Petugas::findOrFail($id_petugas);
        $petugas->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Petugas berhasil dihapus'
        ]);
    }
}
