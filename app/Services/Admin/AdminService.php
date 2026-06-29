<?php

namespace App\Services\Admin;

use App\Constant\Users\UserRole;
use App\Entities\Mahasiswa;
use App\Entities\Pengajar;
use App\Entities\Praktikum;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class AdminService
{
    public function getAllPengajar(): LengthAwarePaginator
    {
        return Pengajar::with(['user', 'praktikum'])->orderBy('nama_lengkap')->paginate(15);
    }

    public function createPengajar(array $data): Pengajar
    {
        $user = User::create([
            'name' => $data['name'] ?? $data['nama_lengkap'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole(UserRole::PENGAJAR);

        return Pengajar::create([
            'user_id' => $user->id,
            'nama_lengkap' => $data['nama_lengkap'],
            'nip' => $data['nip'] ?? null,
            'praktikum_slug' => $data['praktikum_slug'] ?? null,
            'plug' => $data['plug'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function updatePengajar(Pengajar $pengajar, array $data): Pengajar
    {
        $pengajar->update($data);
        return $pengajar->fresh(['user', 'praktikum']);
    }

    public function deletePengajar(Pengajar $pengajar): void
    {
        $pengajar->user->delete();
    }

    public function getAllMahasiswa(): LengthAwarePaginator
    {
        return Mahasiswa::with(['user', 'praktikum'])->orderBy('nim')->paginate(15);
    }

    public function createMahasiswa(array $data): Mahasiswa
    {
        $user = User::create([
            'name' => $data['nama_lengkap'],
            'email' => $data['email'] ?? $data['nim'] . '@labsgg.local',
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole(UserRole::MAHASISWA);

        return Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $data['nim'],
            'nama_lengkap' => $data['nama_lengkap'],
            'angkatan' => $data['angkatan'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function updateMahasiswa(Mahasiswa $mahasiswa, array $data): Mahasiswa
    {
        $mahasiswa->update($data);
        return $mahasiswa->fresh(['user', 'praktikum']);
    }

    public function deleteMahasiswa(Mahasiswa $mahasiswa): void
    {
        $mahasiswa->user->delete();
    }

    public function getAllPraktikum(): LengthAwarePaginator
    {
        return Praktikum::withCount('pengajar', 'mahasiswa')->orderBy('nama')->paginate(15);
    }

    public function createPraktikum(array $data): Praktikum
    {
        return Praktikum::create($data);
    }

    public function updatePraktikum(Praktikum $praktikum, array $data): Praktikum
    {
        $praktikum->update($data);
        return $praktikum->fresh();
    }

    public function deletePraktikum(Praktikum $praktikum): void
    {
        $praktikum->delete();
    }
}
