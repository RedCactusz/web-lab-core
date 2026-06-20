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
        return response()->json(Praktikum::orderBy('nama')->get());
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

        return response()->json($praktikum, 201);
    }

    public function show($id): JsonResponse
    {
        $praktikum = Praktikum::findOrFail($id);
        return response()->json($praktikum);
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

        return response()->json($praktikum);
    }

    public function destroy($id): JsonResponse
    {
        $praktikum = Praktikum::findOrFail($id);
        $praktikum->delete();

        return response()->json(['message' => 'Praktikum berhasil dihapus']);
    }
}
