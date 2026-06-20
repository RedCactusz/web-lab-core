<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Mahasiswa;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MahasiswaRequest;
use App\Http\Resources\MahasiswaResource;
use App\Services\Admin\AdminService;
use Illuminate\Http\JsonResponse;

class MahasiswaController extends Controller
{
    public function __construct(
        protected AdminService $adminService
    ) {}

    public function index(): JsonResponse
    {
        $mahasiswa = $this->adminService->getAllMahasiswa();

        return $this->successResponse(MahasiswaResource::collection($mahasiswa), 'Mahasiswa retrieved');
    }

    public function store(MahasiswaRequest $request): JsonResponse
    {
        $mahasiswa = $this->adminService->createMahasiswa($request->validated());

        return $this->successResponse(new MahasiswaResource($mahasiswa), 'Mahasiswa created successfully');
    }

    public function show(int $id): JsonResponse
    {
        $mahasiswa = Mahasiswa::with(['user', 'praktikum'])->find($id);

        if (!$mahasiswa) {
            return $this->errorResponse('Mahasiswa tidak ditemukan', 404);
        }

        return $this->successResponse(new MahasiswaResource($mahasiswa), 'Mahasiswa retrieved');
    }

    public function update(MahasiswaRequest $request, int $id): JsonResponse
    {
        $mahasiswa = Mahasiswa::find($id);

        if (!$mahasiswa) {
            return $this->errorResponse('Mahasiswa tidak ditemukan', 404);
        }

        $mahasiswa = $this->adminService->updateMahasiswa($mahasiswa, $request->validated());

        return $this->successResponse(new MahasiswaResource($mahasiswa), 'Mahasiswa updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $mahasiswa = Mahasiswa::find($id);

        if (!$mahasiswa) {
            return $this->errorResponse('Mahasiswa tidak ditemukan', 404);
        }

        $this->adminService->deleteMahasiswa($mahasiswa);

        return $this->successResponse(null, 'Mahasiswa deleted successfully');
    }
}
