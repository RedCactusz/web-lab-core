<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\Kerjasama;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class KerjasamaController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Kerjasama::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'date' => 'required|date',
            'partner_id' => 'required|exists:partners,id',
            'image' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $kerjasama = Kerjasama::create($validated);

        return response()->json($kerjasama, 201);
    }

    public function show($id): JsonResponse
    {
        $kerjasama = Kerjasama::findOrFail($id);
        return response()->json($kerjasama);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $kerjasama = Kerjasama::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'date' => 'required|date',
            'partner_id' => 'required|exists:partners,id',
            'image' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $kerjasama->update($validated);

        return response()->json($kerjasama);
    }

    public function destroy($id): JsonResponse
    {
        $kerjasama = Kerjasama::findOrFail($id);
        $kerjasama->delete();

        return response()->json(['message' => 'Kerjasama berhasil dihapus']);
    }
}
