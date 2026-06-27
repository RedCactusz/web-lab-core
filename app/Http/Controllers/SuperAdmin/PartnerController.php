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
        return $this->successResponse(Partner::orderBy('nama', 'asc')->get(), 'Partner retrieved');
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

        return $this->successResponse($partner, 'Partner created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $partner = Partner::findOrFail($id);
        return $this->successResponse($partner, 'Partner retrieved');
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

        return $this->successResponse($partner, 'Partner updated successfully');
    }

    public function destroy($id): JsonResponse
    {
        $partner = Partner::findOrFail($id);
        $partner->delete();

        return $this->successResponse(null, 'Partner berhasil dihapus');
    }
}
