<?php

namespace App\Http\Controllers\Api;

use App\Models\Petugas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register Petugas baru
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_petugas' => 'required|string|max:255',
            'username'     => 'required|string|unique:petugas,username',
            'password'     => 'required|string|min:6|confirmed',
        ]);

        $petugas = Petugas::create([
            'nama_petugas' => $validated['nama_petugas'],
            'username'     => $validated['username'],
            'password'     => Hash::make($validated['password']),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Registrasi berhasil',
            'data'    => [
                'id_petugas'   => $petugas->id_petugas,
                'nama_petugas' => $petugas->nama_petugas,
                'username'     => $petugas->username,
            ]
        ], 201);
    }

    /**
     * Login Petugas
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $petugas = Petugas::where('username', $validated['username'])->first();

        if (!$petugas || !Hash::check($validated['password'], $petugas->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Username atau password salah',
            ], 401);
        }

        $token = $petugas->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => [
                'id_petugas' => $petugas->id_petugas,
                'username' => $petugas->username,
                'token' => $token,
                'token_type' => 'Bearer',
            ]
        ]);
    }

    /**
     * Logout Petugas
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil',
        ]);
    }

    /**
     * Get Current User
     */
    public function me(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->user(),
        ]);
    }
}
