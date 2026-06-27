<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Entities\Inventaris;
use App\Entities\Peminjaman;
use App\Entities\Mahasiswa;
use App\Http\Requests\Public\PeminjamanRequest;
use Illuminate\Http\JsonResponse;

class PublicPeminjamanController extends Controller
{
    public function inventaris(): JsonResponse
    {
        return $this->successResponse(
            Inventaris::select('id', 'kode_alat', 'nama', 'kategori', 'merk', 'tipe', 'kondisi', 'jumlah', 'lokasi')
                ->where('kondisi', '!=', 'rusak_berat')
                ->orderBy('nama')
                ->get(),
            'Inventaris retrieved'
        );
    }

    public function store(PeminjamanRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $mahasiswa = Mahasiswa::where('nim', $validated['nim'])->firstOrFail();

        $peminjaman = Peminjaman::create([
            'mahasiswa_id' => $mahasiswa->id,
            'tanggal_pengajuan' => now()->toDateString(),
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'jam_pinjam' => $validated['jam_pinjam'],
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'jam_kembali' => $validated['jam_kembali'],
            'keperluan' => $validated['keperluan'],
            'alasan_lainnya' => $validated['alasan_lainnya'] ?? null,
            'items' => $validated['items'],
            'status' => 'pending',
        ]);

        return $this->successResponse($peminjaman, 'Peminjaman created successfully', 201);
    }

    public function indexByNim(Request $request): JsonResponse
    {
        $request->validate([
            'nim' => 'required|exists:mahasiswa,nim',
        ]);

        $mahasiswa = Mahasiswa::where('nim', $request->nim)->firstOrFail();

        $peminjaman = Peminjaman::where('mahasiswa_id', $mahasiswa->id)
            ->orderByDesc('created_at')
            ->get();

        return $this->successResponse($peminjaman, 'Peminjaman retrieved');
    }
}
