<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\Partner;
use App\Http\Requests\Admin\PartnerRequest;
use Illuminate\Http\JsonResponse;

class PartnerController extends Controller
{
    public function index(): JsonResponse
    {
        $paginated = Partner::orderBy('nama', 'asc')->paginate(15);
        return $this->successResponse($paginated, 'Partner retrieved');
    }

    public function store(PartnerRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $partner = Partner::create($validated);

        return $this->successResponse($partner, 'Partner created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $partner = Partner::findOrFail($id);
        return $this->successResponse($partner, 'Partner retrieved');
    }

    public function update(PartnerRequest $request, $id): JsonResponse
    {
        $partner = Partner::findOrFail($id);

        $validated = $request->validated();

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
