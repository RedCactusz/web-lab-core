<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Praktikum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PraktikumRequest;
use App\Http\Resources\PraktikumResource;
use App\Services\Admin\AdminService;
use Illuminate\Http\JsonResponse;

class PraktikumController extends Controller
{
    public function __construct(
        protected AdminService $adminService
    ) {}

    public function index(): JsonResponse
    {
        $praktikum = $this->adminService->getAllPraktikum();

        return $this->successResponse(PraktikumResource::collection($praktikum), 'Praktikum retrieved');
    }

    public function store(PraktikumRequest $request): JsonResponse
    {
        $praktikum = $this->adminService->createPraktikum($request->validated());

        return $this->successResponse(new PraktikumResource($praktikum), 'Praktikum created successfully');
    }

    public function show(int $id): JsonResponse
    {
        $praktikum = Praktikum::find($id);

        if (!$praktikum) {
            return $this->errorResponse('Praktikum tidak ditemukan', 404);
        }

        return $this->successResponse(new PraktikumResource($praktikum), 'Praktikum retrieved');
    }

    public function update(PraktikumRequest $request, int $id): JsonResponse
    {
        $praktikum = Praktikum::find($id);

        if (!$praktikum) {
            return $this->errorResponse('Praktikum tidak ditemukan', 404);
        }

        $praktikum = $this->adminService->updatePraktikum($praktikum, $request->validated());

        return $this->successResponse(new PraktikumResource($praktikum), 'Praktikum updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $praktikum = Praktikum::find($id);

        if (!$praktikum) {
            return $this->errorResponse('Praktikum tidak ditemukan', 404);
        }

        $this->adminService->deletePraktikum($praktikum);

        return $this->successResponse(null, 'Praktikum deleted successfully');
    }
}
