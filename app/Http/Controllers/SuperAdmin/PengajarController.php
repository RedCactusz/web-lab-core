<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\Pengajar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PengajarController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->successResponse(Pengajar::with('user', 'praktikum')->orderBy('nama_lengkap')->get(), 'Pengajar retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nama_lengkap' => 'required|string',
                'nip'          => 'nullable|string|unique:pengajar,nip',
                'username'     => 'required|string|unique:users,username',
                'password'     => 'required|string|min:6',
                'praktikum'    => 'nullable|string',
                'plug'         => 'nullable|array',
                'is_active'    => 'boolean',
            ]);

            return DB::transaction(function () use ($validated) {
                $user = User::create([
                    'name'     => $validated['nama_lengkap'],
                    'username' => $validated['username'],
                    'password' => Hash::make($validated['password']),
                ]);

                $user->assignRole('pengajar');

                $pengajar = Pengajar::create([
                    'user_id'      => $user->id,
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'nip'          => $validated['nip'] ?? null,
                    'praktikum_slug' => $validated['praktikum'] ?? null,
                    'plug'         => $validated['plug'] ?? [],
                    'is_active'    => $validated['is_active'] ?? true,
                ]);

                return $this->successResponse($pengajar->load('praktikum'), 'Pengajar created successfully', 201);
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Validation failed for store pengajar', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            throw $e;
        }
    }

    public function show($id): JsonResponse
    {
        $pengajar = Pengajar::with('praktikum')->findOrFail($id);
        return $this->successResponse($pengajar, 'Pengajar retrieved');
    }

    public function update(Request $request, $id): JsonResponse
    {
        $pengajar = Pengajar::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => 'required|string',
            'nip'          => 'nullable|string|unique:pengajar,nip,' . $id,
            'username'     => 'required|string|unique:users,username,' . $pengajar->user_id,
            'password'     => 'nullable|string|min:6',
            'praktikum'    => 'nullable|string',
            'plug'         => 'nullable|array',
            'is_active'    => 'boolean',
        ]);

        return DB::transaction(function () use ($pengajar, $validated) {
            $userData = [
                'name'     => $validated['nama_lengkap'],
                'username' => $validated['username'],
            ];
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }
            $pengajar->user->update($userData);

            $pengajar->update([
                'nama_lengkap' => $validated['nama_lengkap'],
                'nip'          => $validated['nip'] ?? null,
                'praktikum_slug' => $validated['praktikum'] ?? null,
                'plug'         => $validated['plug'] ?? [],
                'is_active'    => $validated['is_active'] ?? $pengajar->is_active,
            ]);

            return $this->successResponse($pengajar->load('praktikum'), 'Pengajar updated successfully');
        });
    }

    public function destroy($id): JsonResponse
    {
        $pengajar = Pengajar::findOrFail($id);
        $user = $pengajar->user;

        $pengajar->delete();

        if ($user) {
            $user->delete();
        }

        return $this->successResponse(null, 'Pengajar berhasil dihapus');
    }
}