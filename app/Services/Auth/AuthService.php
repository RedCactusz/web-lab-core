<?php

namespace App\Services\Auth;

use App\Constant\Users\UserRole;
use App\Entities\Mahasiswa;
use App\Entities\Pengajar;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function loginPengajar(string $username, string $password, ?string $praktikumSlug = null): ?array
    {
        $query = Pengajar::with(['user', 'praktikum'])
            ->whereHas('user', function ($q) use ($username) {
                $q->where('username', $username)
                  ->orWhere('email', $username)
                  ->orWhere('name', $username);
            })
            ->active();

        // Langsung filter by praktikum_slug di query
        if ($praktikumSlug) {
            $query->where('praktikum_slug', $praktikumSlug);
        }

        $pengajar = $query->first();

        if (!$pengajar) {
            return null;
        }

        if (!Hash::check($password, $pengajar->user->password)) {
            return null;
        }

        $token = $pengajar->user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $pengajar->user,
            'pengajar' => $pengajar,
            'token' => $token,
        ];
    }

    public function loginMahasiswa(string $nim, string $password): ?array
    {
        $mahasiswa = Mahasiswa::with('user')
            ->where('nim', $nim)
            ->active()
            ->first();

        if (!$mahasiswa) {
            return null;
        }

        if (!Hash::check($password, $mahasiswa->user->password)) {
            return null;
        }

        $token = $mahasiswa->user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $mahasiswa->user,
            'mahasiswa' => $mahasiswa,
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
    }
}
