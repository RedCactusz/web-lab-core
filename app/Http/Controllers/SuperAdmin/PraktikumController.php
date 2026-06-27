<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\Praktikum;
use App\Http\Requests\SuperAdmin\PraktikumRequest;
use Illuminate\Http\JsonResponse;

class PraktikumController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->successResponse(Praktikum::orderBy('nama')->get(), 'Praktikum retrieved');
    }

    public function store(PraktikumRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $praktikum = Praktikum::create($validated);

        return $this->successResponse($praktikum, 'Praktikum created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $praktikum = Praktikum::findOrFail($id);
        return $this->successResponse($praktikum, 'Praktikum retrieved');
    }

    public function update(PraktikumRequest $request, $id): JsonResponse
    {
        $praktikum = Praktikum::findOrFail($id);

        $validated = $request->validated();

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
