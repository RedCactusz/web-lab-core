<?php

namespace App\Policies;

use App\Entities\Mahasiswa;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MahasiswaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view mahasiswa list
        return $user->hasAnyRole(['super-admin', 'admin', 'pengajar']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Mahasiswa $mahasiswa): bool
    {
        // Super-admin and admin can view any mahasiswa
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // Pengajar can view mahasiswa in their praktikum
        if ($user->hasRole('pengajar')) {
            $pengajar = $user->pengajar;
            if ($pengajar && $pengajar->praktikum) {
                return $mahasiswa->praktikum()->where('slug', $pengajar->praktikum_slug)->exists();
            }
        }

        // Mahasiswa can view their own profile
        if ($user->hasRole('mahasiswa')) {
            return $user->mahasiswa && $user->mahasiswa->id === $mahasiswa->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only super-admin and admin can create mahasiswa
        return $user->hasRole(['super-admin', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Mahasiswa $mahasiswa): bool
    {
        // Super-admin and admin can update any mahasiswa
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // Mahasiswa can update their own profile (limited fields)
        if ($user->hasRole('mahasiswa')) {
            return $user->mahasiswa && $user->mahasiswa->id === $mahasiswa->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Mahasiswa $mahasiswa): bool
    {
        // Only super-admin and admin can delete mahasiswa
        return $user->hasRole(['super-admin', 'admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Mahasiswa $mahasiswa): bool
    {
        // Only super-admin can restore soft-deleted mahasiswa
        return $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Mahasiswa $mahasiswa): bool
    {
        // Only super-admin can force delete
        return $user->hasRole('super-admin');
    }
}
