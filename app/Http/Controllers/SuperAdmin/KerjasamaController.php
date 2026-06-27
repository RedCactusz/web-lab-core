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
        return $this->successResponse(Kerjasama::orderBy('created_at', 'desc')->get(), 'Kerjasama retrieved');
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

        return $this->successResponse($kerjasama, 'Kerjasama created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $kerjasama = Kerjasama::findOrFail($id);
        return $this->successResponse($kerjasama, 'Kerjasama retrieved');
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

        return $this->successResponse($kerjasama, 'Kerjasama updated successfully');
    }

    public function destroy($id): JsonResponse
    {
        $kerjasama = Kerjasama::findOrFail($id);
        $kerjasama->delete();

        return $this->successResponse(null, 'Kerjasama berhasil dihapus');
    }
}
