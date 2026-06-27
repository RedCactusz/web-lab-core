<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\Inventaris;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InventarisController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->successResponse(Inventaris::orderBy('nama')->get(), 'Inventaris retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode_alat' => 'required|string|unique:inventaris,kode_alat',
            'nama' => 'required|string',
            'kategori' => 'required|in:surveying,aksesoris,perlengkapan,lainnya',
            'merk' => 'nullable|string',
            'tipe' => 'nullable|string',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat,maintenance',
            'jumlah' => 'required|integer|min:0',
            'lokasi' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|array',
        ]);

        $inventaris = Inventaris::create($validated);

        return $this->successResponse($inventaris, 'Inventaris created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $inventaris = Inventaris::findOrFail($id);
        return $this->successResponse($inventaris, 'Inventaris retrieved');
    }

    public function update(Request $request, $id): JsonResponse
    {
        $inventaris = Inventaris::findOrFail($id);

        $validated = $request->validate([
            'kode_alat' => 'required|string|unique:inventaris,kode_alat,' . $id,
            'nama' => 'required|string',
            'kategori' => 'required|in:surveying,aksesoris,perlengkapan,lainnya',
            'merk' => 'nullable|string',
            'tipe' => 'nullable|string',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat,maintenance',
            'jumlah' => 'required|integer|min:0',
            'lokasi' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|array',
        ]);

        $inventaris->update($validated);

        return $this->successResponse($inventaris, 'Inventaris updated successfully');
    }

    public function destroy($id): JsonResponse
    {
        $inventaris = Inventaris::findOrFail($id);
        $inventaris->delete();

        return $this->successResponse(null, 'Inventaris berhasil dihapus');
    }
}
