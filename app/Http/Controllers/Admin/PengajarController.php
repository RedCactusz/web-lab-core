<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Pengajar;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PengajarRequest;
use App\Http\Resources\PengajarResource;
use App\Services\Admin\AdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PengajarController extends Controller
{
    public function __construct(
        protected AdminService $adminService
    ) {}

    public function index(): JsonResponse
    {
        $pengajar = $this->adminService->getAllPengajar();

        return $this->successResponse(PengajarResource::collection($pengajar), 'Pengajar retrieved');
    }

    public function store(PengajarRequest $request): JsonResponse
    {
        $pengajar = $this->adminService->createPengajar($request->validated());

        return $this->successResponse(new PengajarResource($pengajar), 'Pengajar created successfully');
    }

    public function show(int $id): JsonResponse
    {
        $pengajar = Pengajar::with(['user', 'praktikum'])->find($id);

        if (!$pengajar) {
            return $this->errorResponse('Pengajar tidak ditemukan', 404);
        }

        return $this->successResponse(new PengajarResource($pengajar), 'Pengajar retrieved');
    }

    public function update(PengajarRequest $request, int $id): JsonResponse
    {
        $pengajar = Pengajar::find($id);

        if (!$pengajar) {
            return $this->errorResponse('Pengajar tidak ditemukan', 404);
        }

        $pengajar = $this->adminService->updatePengajar($pengajar, $request->validated());

        return $this->successResponse(new PengajarResource($pengajar), 'Pengajar updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $pengajar = Pengajar::find($id);

        if (!$pengajar) {
            return $this->errorResponse('Pengajar tidak ditemukan', 404);
        }

        $this->adminService->deletePengajar($pengajar);

        return $this->successResponse(null, 'Pengajar deleted successfully');
    }
}
