<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\Pengajar;
use App\Entities\Mahasiswa;
use App\Entities\Inventaris;
use App\Entities\Peminjaman;
use App\Entities\Praktikum;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SuperAdminAuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        $user = \App\Models\User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Username atau password salah'],
            ]);
        }

        if (!$user->is_verify) {
            throw ValidationException::withMessages([
                'username' => ['Akun Anda belum diverifikasi oleh sistem'],
            ]);
        }

        if (!$user->hasRole('super-admin')) {
            throw ValidationException::withMessages([
                'username' => ['Akun tidak memiliki akses super admin'],
            ]);
        }

        $token = $user->createToken('super-admin-token')->plainTextToken;

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'role' => 'super-admin',
            ],
            'token' => $token,
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'is_verify' => false,
        ]);

        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $user->assignRole($role);

        return response()->json([
            'message' => 'Registrasi berhasil. Akun Anda menunggu verifikasi administrator.',
            'user' => $user,
        ], 201);
    }

    public function getStats(): JsonResponse
    {
        return response()->json([
            'pengajar' => Pengajar::where('is_active', true)->count(),
            'mahasiswa' => Mahasiswa::where('is_active', true)->count(),
            'inventaris_baik' => Inventaris::where('kondisi', 'baik')->sum('jumlah'),
            'inventaris_rusak' => Inventaris::whereIn('kondisi', ['rusak_ringan', 'rusak_berat'])->sum('jumlah'),
            'peminjaman_pending' => Peminjaman::where('status', 'pending')->count(),
            'peminjaman_approved' => Peminjaman::where('status', 'approved')->count(),
            'praktikum' => Praktikum::where('is_active', true)->count(),
        ]);
    }

    public function checkUsername(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|string',
        ]);

        $exists = \App\Models\User::where('username', $request->username)->exists();

        return response()->json([
            'exists' => $exists,
            'available' => !$exists,
        ]);
    }
}
