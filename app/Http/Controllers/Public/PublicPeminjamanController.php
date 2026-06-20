<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Entities\Inventaris;
use App\Entities\Peminjaman;
use App\Entities\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PublicPeminjamanController extends Controller
{
    public function inventaris(): JsonResponse
    {
        return response()->json(
            Inventaris::select('id', 'kode_alat', 'nama', 'kategori', 'merk', 'tipe', 'kondisi', 'jumlah', 'lokasi')
                ->where('kondisi', '!=', 'rusak_berat')
                ->orderBy('nama')
                ->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nim' => 'required|exists:mahasiswa,nim',
            'nama_mahasiswa' => 'required|string',
            'keperluan' => 'required|string',
            'alasan_lainnya' => 'nullable|string',
            'tanggal_pinjam' => 'required|date',
            'jam_pinjam' => 'required|string',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jam_kembali' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.nama_alat' => 'required|string',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

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

        return response()->json($peminjaman, 201);
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

        return response()->json($peminjaman);
    }
}
