<?php

namespace App\Policies;

use App\Entities\Peminjaman;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PeminjamanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view peminjaman list (filtered by role)
        return $user->hasAnyRole(['super-admin', 'admin', 'pengajar', 'mahasiswa']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        // Super-admin and admin can view any peminjaman
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // Pengajar can view peminjaman from their praktikum's mahasiswa
        if ($user->hasRole('pengajar')) {
            $pengajar = $user->pengajar;
            if ($pengajar && $pengajar->praktikum) {
                return $peminjaman->mahasiswa->praktikum()
                    ->where('slug', $pengajar->praktikum_slug)
                    ->exists();
            }
        }

        // Mahasiswa can view their own peminjaman
        if ($user->hasRole('mahasiswa')) {
            return $user->mahasiswa && $user->mahasiswa->id === $peminjaman->mahasiswa_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Mahasiswa can create peminjaman for themselves
        // Super-admin and admin can also create
        return $user->hasAnyRole(['super-admin', 'admin', 'mahasiswa']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Peminjaman $peminjaman): bool
    {
        // Super-admin and admin can update any peminjaman
        if ($user->hasRole(['super-admin', 'admin'])) {
            return true;
        }

        // Pengajar can approve/update peminjaman from their praktikum's mahasiswa
        if ($user->hasRole('pengajar')) {
            $pengajar = $user->pengajar;
            if ($pengajar && $pengajar->praktikum) {
                return $peminjaman->mahasiswa->praktikum()
                    ->where('slug', $pengajar->praktikum_slug)
                    ->exists();
            }
        }

        // Mahasiswa can update their own peminjaman (limited, e.g., cancel pending)
        if ($user->hasRole('mahasiswa') && $peminjaman->status === 'pending') {
            return $user->mahasiswa && $user->mahasiswa->id === $peminjaman->mahasiswa_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Peminjaman $peminjaman): bool
    {
        // Only super-admin and admin can delete peminjaman
        return $user->hasRole(['super-admin', 'admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Peminjaman $peminjaman): bool
    {
        // Only super-admin can restore soft-deleted peminjaman
        return $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Peminjaman $peminjaman): bool
    {
        // Only super-admin can force delete
        return $user->hasRole('super-admin');
    }
}
