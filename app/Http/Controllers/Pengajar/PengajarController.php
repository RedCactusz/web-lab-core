<?php

namespace App\Http\Controllers\Pengajar;

use App\Entities\PraktikumMinggu;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pengajar\UpdateGradeRequest;
use App\Services\Pengajar\PengajarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PengajarController extends Controller
{
    public function __construct(
        protected PengajarService $pengajarService
    ) {}

    public function me(Request $request): JsonResponse
    {
        $pengajar = $request->user()->pengajar;

        if (!$pengajar) {
            return $this->errorResponse('Data pengajar tidak ditemukan', 404);
        }

        $profile = $this->pengajarService->getMyProfile($pengajar);

        return $this->successResponse($profile, 'Profile retrieved');
    }

    public function students(Request $request, string $praktikumSlug): JsonResponse
    {
        $pengajar = $request->user()->pengajar;

        if (!$pengajar || !$pengajar->praktikum) {
            return $this->errorResponse('Pengajar belum memiliki praktikum', 403);
        }

        $praktikum = $pengajar->praktikum;
        $plugs = $pengajar->plug;

        $students = $this->pengajarService->getStudentsByPraktikum($praktikum->id, $plugs);

        return $this->successResponse($students, 'Students retrieved');
    }

    public function studentDetail(Request $request, string $praktikumSlug, string $nim): JsonResponse
    {
        $pengajar = $request->user()->pengajar;

        if (!$pengajar || !$pengajar->praktikum) {
            return $this->errorResponse('Pengajar belum memiliki praktikum', 403);
        }

        $student = $this->pengajarService->getStudentByNim($pengajar->praktikum->id, $nim);

        if (!$student) {
            return $this->errorResponse('Mahasiswa tidak ditemukan', 404);
        }

        return $this->successResponse($student, 'Student retrieved');
    }

    public function updateGrade(UpdateGradeRequest $request, string $praktikumSlug, string $nim): JsonResponse
    {
        $pengajar = $request->user()->pengajar;

        if (!$pengajar || !$pengajar->praktikum) {
            return $this->errorResponse('Pengajar belum memiliki praktikum', 403);
        }

        $nilai = $this->pengajarService->updateStudentGrade(
            $pengajar->praktikum->id,
            $nim,
            $request->validated()
        );

        if (!$nilai) {
            return $this->errorResponse('Mahasiswa tidak ditemukan', 404);
        }

        return $this->successResponse($nilai, 'Grade updated successfully');
    }

    public function stats(Request $request, string $praktikumSlug): JsonResponse
    {
        $pengajar = $request->user()->pengajar;

        if (!$pengajar || !$pengajar->praktikum) {
            return $this->errorResponse('Pengajar belum memiliki praktikum', 403);
        }

        $stats = $this->pengajarService->getStats($pengajar->praktikum->id);

        return $this->successResponse($stats, 'Stats retrieved');
    }

    public function listMinggu(Request $request, string $praktikumSlug): JsonResponse
    {
        $pengajar = $request->user()->pengajar;

        if (!$pengajar || !$pengajar->praktikum) {
            return $this->errorResponse('Pengajar belum memiliki praktikum', 403);
        }

        $praktikum = $pengajar->praktikum;
        $mingguList = PraktikumMinggu::byPraktikum($praktikum->id)
            ->with('parameters')
            ->active()
            ->get();

        return $this->successResponse($mingguList, 'List minggu retrieved');
    }

    public function getMingguParameters(Request $request, string $praktikumSlug, int $mingguId): JsonResponse
    {
        $pengajar = $request->user()->pengajar;

        if (!$pengajar || !$pengajar->praktikum) {
            return $this->errorResponse('Pengajar belum memiliki praktikum', 403);
        }

        $praktikum = $pengajar->praktikum;
        $minggu = PraktikumMinggu::where('id', $mingguId)
            ->where('praktikum_id', $praktikum->id)
            ->with('parameters')
            ->first();

        if (!$minggu) {
            return $this->errorResponse('Minggu tidak ditemukan', 404);
        }

        return $this->successResponse($minggu, 'Minggu parameters retrieved');
    }
}
