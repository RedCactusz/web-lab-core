<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PeminjamanController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->successResponse(Peminjaman::with('mahasiswa')->orderByDesc('created_at')->get(), 'Peminjaman retrieved');
    }

    public function show($id): JsonResponse
    {
        $peminjaman = Peminjaman::with('mahasiswa')->findOrFail($id);
        return $this->successResponse($peminjaman, 'Peminjaman retrieved');
    }

    public function update(Request $request, $id): JsonResponse
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,decline,completed,miss',
            'revisi_catatan' => 'nullable|string',
            'revised_items' => 'nullable|array',
            'pengembalian_catatan' => 'nullable|string',
            'pengembalian_items' => 'nullable|array',
            'tanggal_dikembalikan' => 'nullable|date',
        ]);

        $peminjaman->update($validated);

        return $this->successResponse($peminjaman, 'Peminjaman updated successfully');
    }

    public function destroy($id): JsonResponse
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();

        return $this->successResponse(null, 'Peminjaman berhasil dihapus');
    }
}
