<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Entities\Mahasiswa;
use App\Entities\Pengajar;
use App\Http\Requests\Api\LoginMahasiswaRequest;
use App\Http\Requests\Api\LoginPengajarRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function loginPengajar(LoginPengajarRequest $request): JsonResponse
    {
        $result = $this->authService->loginPengajar(
            $request->validated('username'),
            $request->validated('password'),
            $request->validated('praktikum_slug')
        );

        if (!$result) {
            return $this->errorResponse('Username, password, atau praktikum salah', 401);
        }

        return $this->successResponse([
            'user' => $result['user'],
            'pengajar' => $result['pengajar'],
            'token' => $result['token'],
        ], 'Login berhasil');
    }

    public function loginMahasiswa(LoginMahasiswaRequest $request): JsonResponse
    {
        $result = $this->authService->loginMahasiswa(
            $request->validated('nim'),
            $request->validated('password')
        );

        if (!$result) {
            return $this->errorResponse('NIM atau password salah', 401);
        }

        return $this->successResponse([
            'user' => $result['user'],
            'mahasiswa' => $result['mahasiswa'],
            'token' => $result['token'],
        ], 'Login berhasil');
    }

    public function registerPengajar(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'praktikum' => 'required|string|exists:praktikum,slug',
            'nip' => 'nullable|string|max:255',
            'plug' => 'nullable|array',
            'plug.*' => 'integer|min:1',
        ]);

        // Cek apakah user dengan username ini sudah ada
        $existingUser = \App\Models\User::where('username', $validated['username'])->first();

        if ($existingUser) {
            // User sudah ada, cek apakah sudah terdaftar di praktikum yang sama
            $existingPengajar = Pengajar::where('user_id', $existingUser->id)
                ->where('praktikum_slug', $validated['praktikum'])
                ->first();

            if ($existingPengajar) {
                return $this->errorResponse('Anda sudah terdaftar di praktikum ini', 409);
            }

            // Tambah entry pengajar baru untuk user yang sama
            $plug = $validated['plug'] ?? [];
            sort($plug);

            $pengajar = Pengajar::create([
                'user_id' => $existingUser->id,
                'nama_lengkap' => $validated['nama_lengkap'],
                'nip' => $validated['nip'] ?? null,
                'praktikum_slug' => $validated['praktikum'],
                'plug' => json_encode($plug),
                'is_active' => true,
            ]);

            return $this->successResponse([
                'user' => $existingUser,
                'pengajar' => $pengajar,
            ], 'Berhasil ditambahkan ke praktikum baru', 201);
        }

        // User belum ada, buat User + Pengajar baru
        $user = \App\Models\User::create([
            'name' => $validated['nama_lengkap'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'is_verify' => true,
        ]);

        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'pengajar', 'guard_name' => 'web']);
        $user->assignRole($role);

        $plug = $validated['plug'] ?? [];
        sort($plug);

        $pengajar = Pengajar::create([
            'user_id' => $user->id,
            'nama_lengkap' => $validated['nama_lengkap'],
            'nip' => $validated['nip'] ?? null,
            'praktikum_slug' => $validated['praktikum'],
            'plug' => json_encode($plug),
            'is_active' => true,
        ]);

        return $this->successResponse([
            'user' => $user,
            'pengajar' => $pengajar,
        ], 'Registrasi berhasil', 201);
    }

    public function registerMahasiswa(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nim' => 'required|string|unique:mahasiswa,nim',
            'nama_lengkap' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (Mahasiswa::where('nim', $validated['nim'])->exists()) {
            return $this->errorResponse('NIM sudah terdaftar', 409);
        }

        return DB::transaction(function () use ($validated) {
            $nim = $validated['nim'];
            $angkatan = strlen($nim) >= 5 ? 2000 + (int)substr($nim, 3, 2) : null;
            $email = $nim . '@student.upnyk.ac.id';

            $user = \App\Models\User::create([
                'name' => $validated['nama_lengkap'],
                'username' => $nim,
                'email' => $email,
                'password' => Hash::make($validated['password']),
                'is_verify' => true,
            ]);

            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'mahasiswa', 'guard_name' => 'web']);
            $user->assignRole($role);

            $mahasiswa = Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $nim,
                'nama_lengkap' => $validated['nama_lengkap'],
                'angkatan' => $angkatan,
                'is_active' => true,
            ]);

            return $this->successResponse([
                'user' => $user,
                'mahasiswa' => $mahasiswa,
            ], 'Registrasi mahasiswa berhasil', 201);
        });
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return $this->successResponse(null, 'Logout berhasil');
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load(['pengajar', 'mahasiswa', 'roles']);

        return $this->successResponse([
            'user' => $user,
            'role' => $user->roles->first()?->name ?? null,
        ], 'User retrieved');
    }
}
