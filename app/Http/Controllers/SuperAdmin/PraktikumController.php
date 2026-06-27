<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\Praktikum;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PraktikumController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->successResponse(Praktikum::orderBy('nama')->get(), 'Praktikum retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:praktikum,kode',
            'nama' => 'required|string',
            'slug' => 'required|string|unique:praktikum,slug',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
            'jumlah_plug' => 'nullable|integer|min:1',
        ]);

        $praktikum = Praktikum::create($validated);

        return $this->successResponse($praktikum, 'Praktikum created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $praktikum = Praktikum::findOrFail($id);
        return $this->successResponse($praktikum, 'Praktikum retrieved');
    }

    public function update(Request $request, $id): JsonResponse
    {
        $praktikum = Praktikum::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'required|string|unique:praktikum,kode,' . $id,
            'nama' => 'required|string',
            'slug' => 'required|string|unique:praktikum,slug,' . $id,
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
            'jumlah_plug' => 'nullable|integer|min:1',
        ]);

        $praktikum->update($validated);

        return $this->successResponse($praktikum, 'Praktikum updated successfully');
    }

    public function destroy($id): JsonResponse
    {
        $praktikum = Praktikum::findOrFail($id);
        $praktikum->delete();

        return $this->successResponse(null, 'Praktikum berhasil dihapus');
    }
}
