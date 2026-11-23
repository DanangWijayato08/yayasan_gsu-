<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Pengontrol Autentikasi API
 * 
 * Menangani proses login, logout, dan mengambil data profil pengguna yang sedang login.
 * Menggunakan Laravel Sanctum untuk manajemen token bearer.
 */
class AuthController extends Controller
{
    /**
     * Login pengguna dan hasilkan token akses
     * 
     * Memvalidasi kredensial email dan password, kemudian membuat token akses
     * yang dapat digunakan untuk request ke endpoint yang memerlukan autentikasi.
     * 
     * @param Request $request - Request berisi email dan password pengguna
     * @return \Illuminate\Http\JsonResponse - Token dan data pengguna jika berhasil, error 401 jika gagal
     */
    public function login(Request $request)
    {
        // Validasi input: email dan password harus diisi
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Cari pengguna berdasarkan email
        $user = User::where('email', $validated['email'])->first();

        // Verifikasi pengguna ada dan password cocok
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        // Pastikan hanya admin yang bisa login melalui endpoint /api/admin/login
        if ($request->is('api/admin/login') && $user->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak. Akun ini bukan administrator.'], 403);
        }

        // Buat token akses baru untuk pengguna
        $token = $user->createToken('api-token')->plainTextToken;
        
        return response()->json(['token' => $token, 'user' => $user], 200);
    }

    /**
     * Logout pengguna dan hapus token akses aktif
     * 
     * Menghapus token akses yang sedang digunakan sehingga pengguna tidak bisa
     * mengakses endpoint yang memerlukan autentikasi dengan token lama.
     * 
     * @param Request $request - Request dari pengguna yang sedang login
     * @return \Illuminate\Http\JsonResponse - Pesan sukses logout
     */
    public function logout(Request $request)
    {
        // Hapus token akses yang sedang aktif
        $request->user()->currentAccessToken()?->delete();
        
        return response()->json(['message' => 'Logout berhasil'], 200);
    }

    /**
     * Ambil data profil pengguna yang sedang login
     * 
     * Mengembalikan informasi lengkap pengguna yang sedang terautentikasi
     * termasuk ID, email, nama, dan peran (role).
     * 
     * @param Request $request - Request dari pengguna yang sedang login
     * @return \Illuminate\Http\JsonResponse - Data profil pengguna
     */
    public function me(Request $request)
    {
        return response()->json($request->user(), 200);
    }
}

