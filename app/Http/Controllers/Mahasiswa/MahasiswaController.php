<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\PeminjamanRequest;
use App\Services\Mahasiswa\MahasiswaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function __construct(
        protected MahasiswaService $mahasiswaService
    ) {}

    public function me(Request $request): JsonResponse
    {
        $mahasiswa = $request->user()->mahasiswa;

        if (!$mahasiswa) {
            return $this->errorResponse('Data mahasiswa tidak ditemukan', 404);
        }

        $profile = $this->mahasiswaService->getMyProfile($mahasiswa);

        return $this->successResponse($profile, 'Profile retrieved');
    }

    public function grades(Request $request): JsonResponse
    {
        $mahasiswa = $request->user()->mahasiswa;

        if (!$mahasiswa) {
            return $this->errorResponse('Data mahasiswa tidak ditemukan', 404);
        }

        $grades = $this->mahasiswaService->getMyGrades($mahasiswa);

        return $this->successResponse($grades, 'Grades retrieved');
    }

    public function peminjamanIndex(Request $request): JsonResponse
    {
        $mahasiswa = $request->user()->mahasiswa;

        if (!$mahasiswa) {
            return $this->errorResponse('Data mahasiswa tidak ditemukan', 404);
        }

        $peminjaman = $this->mahasiswaService->getMyPeminjaman($mahasiswa);

        return $this->successResponse($peminjaman, 'Peminjaman retrieved');
    }

    public function peminjamanStore(PeminjamanRequest $request): JsonResponse
    {
        $mahasiswa = $request->user()->mahasiswa;

        if (!$mahasiswa) {
            return $this->errorResponse('Data mahasiswa tidak ditemukan', 404);
        }

        $peminjaman = $this->mahasiswaService->createPeminjaman($mahasiswa, $request->validated());

        return $this->successResponse($peminjaman, 'Peminjaman created successfully', 201);
    }

    public function peminjamanUpdate(PeminjamanRequest $request, int $id): JsonResponse
    {
        $mahasiswa = $request->user()->mahasiswa;

        if (!$mahasiswa) {
            return $this->errorResponse('Data mahasiswa tidak ditemukan', 404);
        }

        $peminjaman = $this->mahasiswaService->updatePeminjaman($mahasiswa, $id, $request->validated());

        if (!$peminjaman) {
            return $this->errorResponse('Peminjaman tidak ditemukan', 404);
        }

        return $this->successResponse($peminjaman, 'Peminjaman updated successfully');
    }
}
