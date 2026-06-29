<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\Inventaris;
use App\Http\Requests\Admin\InventarisRequest;
use Illuminate\Http\JsonResponse;

class InventarisController extends Controller
{
    public function index(): JsonResponse
    {
        $paginated = Inventaris::orderBy('nama')->paginate(15);
        return $this->successResponse($paginated, 'Inventaris retrieved');
    }

    public function store(InventarisRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $inventaris = Inventaris::create($validated);

        return $this->successResponse($inventaris, 'Inventaris created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $inventaris = Inventaris::findOrFail($id);
        return $this->successResponse($inventaris, 'Inventaris retrieved');
    }

    public function update(InventarisRequest $request, $id): JsonResponse
    {
        $inventaris = Inventaris::findOrFail($id);

        $validated = $request->validated();

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
