<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\Mahasiswa;
use App\Http\Requests\SuperAdmin\MahasiswaRequest;
use App\Services\Mahasiswa\MahasiswaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function __construct(
        protected MahasiswaService $mahasiswaService
    ) {}

    public function index(): JsonResponse
    {
        $paginated = Mahasiswa::with('user')->orderBy('nama_lengkap')->paginate(15);
        return $this->successResponse($paginated, 'Mahasiswa retrieved');
    }

    public function store(MahasiswaRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $mahasiswa = $this->mahasiswaService->createWithUser($validated);

        return $this->successResponse($mahasiswa->load('user'), 'Mahasiswa created successfully', 201);
    }

    public function show(int $id): JsonResponse
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);
        return $this->successResponse($mahasiswa, 'Mahasiswa retrieved');
    }

    public function update(MahasiswaRequest $request, int $id): JsonResponse
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $validated = $request->validated();

        $updatedMahasiswa = $this->mahasiswaService->updateWithUser($mahasiswa, $validated);

        return $this->successResponse($updatedMahasiswa, 'Mahasiswa updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->user->delete();

        return $this->successResponse(null, 'Mahasiswa berhasil dihapus');
    }

    public function importCsv(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');

        try {
            $results = $this->mahasiswaService->importFromCsv($file);

            return $this->successResponse([
                'message' => 'Import selesai',
                'results' => $results,
            ], 'Import CSV selesai');

        } catch (\Exception $e) {
            return $this->errorResponse('Gagal import: ' . $e->getMessage(), 500);
        }
    }
}
