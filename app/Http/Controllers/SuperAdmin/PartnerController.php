<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\Partner;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PartnerController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Partner::orderBy('nama', 'asc')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'logo' => 'required|string',
            'website' => 'nullable|url',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $partner = Partner::create($validated);

        return response()->json($partner, 201);
    }

    public function show($id): JsonResponse
    {
        $partner = Partner::findOrFail($id);
        return response()->json($partner);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $partner = Partner::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string',
            'logo' => 'required|string',
            'website' => 'nullable|url',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $partner->update($validated);

        return response()->json($partner);
    }

    public function destroy($id): JsonResponse
    {
        $partner = Partner::findOrFail($id);
        $partner->delete();

        return response()->json(['message' => 'Partner berhasil dihapus']);
    }
}
