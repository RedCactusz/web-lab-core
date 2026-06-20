<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use App\Entities\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PeminjamanController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $pengajar = $request->user()->pengajar;

        if (!$pengajar || !$pengajar->praktikum_slug) {
            return $this->errorResponse('Akun pengajar belum terhubung ke praktikum. Hubungi administrator untuk mengatur praktikum Anda.', 403);
        }

        $praktikumSlug = $pengajar->praktikum_slug;

        $peminjaman = Peminjaman::with('mahasiswa')
            ->where(function($q) use ($praktikumSlug) {
                $q->where('keperluan', $praktikumSlug)
                  ->orWhere('keperluan', 'lainnya');
            })
            ->orderByDesc('created_at')
            ->get();

        return $this->successResponse($peminjaman, 'Peminjaman retrieved');
    }

    public function updateStatus(Request $request, $id): JsonResponse
    {
        $pengajar = $request->user()->pengajar;

        if (!$pengajar || !$pengajar->praktikum_slug) {
            return $this->errorResponse('Akun pengajar belum terhubung ke praktikum. Hubungi administrator untuk mengatur praktikum Anda.', 403);
        }

        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->keperluan !== $pengajar->praktikum_slug && $peminjaman->keperluan !== 'lainnya') {
            return $this->errorResponse('Anda tidak memiliki akses ke peminjaman ini', 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,decline,completed,miss',
            'revisi_catatan' => 'nullable|string',
            'revised_items' => 'nullable|array',
            'pengembalian_catatan' => 'nullable|string',
            'pengembalian_items' => 'nullable|array',
            'tanggal_dikembalikan' => 'nullable|date',
        ]);

        $peminjaman->update($validated);

        return $this->successResponse($peminjaman, 'Status updated');
    }
}
