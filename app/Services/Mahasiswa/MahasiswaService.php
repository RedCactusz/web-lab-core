<?php

namespace App\Services\Mahasiswa;

use App\Entities\Mahasiswa;
use App\Entities\Nilai;
use App\Entities\Peminjaman;
use Illuminate\Support\Collection;

class MahasiswaService
{
    public function getMyProfile(Mahasiswa $mahasiswa): Mahasiswa
    {
        return $mahasiswa->load(['user', 'praktikum']);
    }

    public function getMyGrades(Mahasiswa $mahasiswa): Collection
    {
        return Nilai::with(['praktikum'])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getMyPeminjaman(Mahasiswa $mahasiswa): Collection
    {
        return Peminjaman::where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createPeminjaman(Mahasiswa $mahasiswa, array $data): Peminjaman
    {
        return Peminjaman::create([
            'mahasiswa_id' => $mahasiswa->id,
            'tanggal_pengajuan' => $data['tanggal_pengajuan'] ?? now()->toDateString(),
            'nama_alat' => $data['nama_alat'],
            'jumlah' => $data['jumlah'],
            'tanggal_pinjam' => $data['tanggal_pinjam'] ?? null,
            'tanggal_kembali' => $data['tanggal_kembali'] ?? null,
            'status' => 'pending',
            'keterangan' => $data['keterangan'] ?? null,
        ]);
    }

    public function updatePeminjaman(Mahasiswa $mahasiswa, int $peminjamanId, array $data): ?Peminjaman
    {
        $peminjaman = Peminjaman::where('mahasiswa_id', $mahasiswa->id)
            ->where('id', $peminjamanId)
            ->first();

        if (!$peminjaman) {
            return null;
        }

        $peminjaman->update($data);

        return $peminjaman;
    }
}
