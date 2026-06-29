<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\Peminjaman;
use App\Http\Requests\Admin\PeminjamanRequest;
use Illuminate\Http\JsonResponse;

class PeminjamanController extends Controller
{
    public function index(): JsonResponse
    {
        $paginated = Peminjaman::with('mahasiswa')->orderByDesc('created_at')->paginate(15);
        return $this->successResponse($paginated, 'Peminjaman retrieved');
    }

    public function show($id): JsonResponse
    {
        $peminjaman = Peminjaman::with('mahasiswa')->findOrFail($id);
        return $this->successResponse($peminjaman, 'Peminjaman retrieved');
    }

    public function update(PeminjamanRequest $request, $id): JsonResponse
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $validated = $request->validated();

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
