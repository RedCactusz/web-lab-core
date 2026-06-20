<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Entities\Praktikum;
use App\Entities\PraktikumJadwal;
use App\Entities\PraktikumMinggu;
use App\Http\Controllers\Controller;
use App\Services\PenilaianService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PraktikumManagementController extends Controller
{
    public function __construct(
        protected PenilaianService $penilaianService
    ) {}

    public function detail(string $slug): JsonResponse
    {
        $praktikum = Praktikum::where('slug', $slug)->firstOrFail();
        $detail = $this->penilaianService->getDetailPraktikum($praktikum->id);

        return $this->successResponse($detail, 'Detail praktikum retrieved');
    }

    public function listMinggu(string $slug): JsonResponse
    {
        $praktikum = Praktikum::where('slug', $slug)->firstOrFail();
        $mingguList = PraktikumMinggu::byPraktikum($praktikum->id)
            ->with('parameters')
            ->get();

        return $this->successResponse($mingguList, 'List minggu retrieved');
    }

    public function generateMinggu(string $slug): JsonResponse
    {
        $praktikum = Praktikum::where('slug', $slug)->firstOrFail();

        try {
            $mingguList = $this->penilaianService->generateMinggu($praktikum->id);
            return $this->successResponse($mingguList, 'Minggu berhasil di-generate');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function updateMinggu(Request $request, int $id): JsonResponse
    {
        $minggu = PraktikumMinggu::findOrFail($id);

        $validated = $request->validate([
            'topik' => 'nullable|string',
            'tanggal' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $minggu->update($validated);

        return $this->successResponse($minggu->load('parameters'), 'Minggu berhasil diupdate');
    }

    public function addParameter(Request $request, int $mingguId): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'bobot' => 'required|numeric|min:0.001|max:1',
            'tipe' => 'nullable|string|in:numeric,presensi,text',
            'max_nilai' => 'nullable|integer|min:1',
            'urutan' => 'nullable|integer|min:0',
        ]);

        try {
            $parameter = $this->penilaianService->addParameter($mingguId, $validated);
            return $this->successResponse($parameter, 'Parameter berhasil ditambahkan');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function updateParameter(Request $request, int $parameterId): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'nullable|string',
            'bobot' => 'nullable|numeric|min:0.001|max:1',
            'tipe' => 'nullable|string|in:numeric,presensi,text',
            'max_nilai' => 'nullable|integer|min:1',
            'urutan' => 'nullable|integer|min:0',
        ]);

        try {
            $parameter = $this->penilaianService->updateParameter($parameterId, $validated);
            return $this->successResponse($parameter, 'Parameter berhasil diupdate');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function deleteParameter(int $parameterId): JsonResponse
    {
        try {
            $this->penilaianService->deleteParameter($parameterId);
            return $this->successResponse(null, 'Parameter berhasil dihapus');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function generatePenilaian(int $mingguId): JsonResponse
    {
        try {
            $result = $this->penilaianService->generatePenilaian($mingguId);
            return $this->successResponse($result, 'Penilaian berhasil di-generate dan disinkronisasi');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function preview(string $slug): JsonResponse
    {
        $praktikum = Praktikum::where('slug', $slug)->firstOrFail();
        $preview = $this->penilaianService->previewPenilaian($praktikum->id);

        return $this->successResponse($preview, 'Preview penilaian retrieved');
    }

    public function listJadwal(string $slug): JsonResponse
    {
        $praktikum = Praktikum::where('slug', $slug)->firstOrFail();
        $jadwal = PraktikumJadwal::byPraktikum($praktikum->id)->get();

        return $this->successResponse($jadwal, 'List jadwal retrieved');
    }

    public function storeJadwal(Request $request, string $slug): JsonResponse
    {
        $praktikum = Praktikum::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i',
            'ruangan' => 'nullable|string',
            'topik' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $jadwal = PraktikumJadwal::create([
            'praktikum_id' => $praktikum->id,
            ...$validated,
        ]);

        return $this->successResponse($jadwal, 'Jadwal berhasil ditambahkan', 201);
    }

    public function updateJadwal(Request $request, int $id): JsonResponse
    {
        $jadwal = PraktikumJadwal::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'nullable|date',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i',
            'ruangan' => 'nullable|string',
            'topik' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $jadwal->update($validated);

        return $this->successResponse($jadwal, 'Jadwal berhasil diupdate');
    }

    public function deleteJadwal(int $id): JsonResponse
    {
        $jadwal = PraktikumJadwal::findOrFail($id);
        $jadwal->delete();

        return $this->successResponse(null, 'Jadwal berhasil dihapus');
    }

    public function assignMahasiswaToKelompok(Request $request, string $slug): JsonResponse
    {
        $praktikum = Praktikum::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'mahasiswa_id' => 'required|integer|exists:mahasiswa,id',
            'kelompok' => 'required|integer|min:1',
            'plug' => 'nullable|integer|min:1',
        ]);

        $praktikum->mahasiswa()->syncWithoutDetaching([
            $validated['mahasiswa_id'] => [
                'kelompok' => $validated['kelompok'],
                'plug' => $validated['plug'] ?? null,
            ],
        ]);

        $mahasiswa = $praktikum->mahasiswa()->where('mahasiswa.id', $validated['mahasiswa_id'])->first();

        return $this->successResponse($mahasiswa, 'Mahasiswa berhasil dimasukkan ke kelompok');
    }

    public function removeMahasiswaFromKelompok(Request $request, string $slug): JsonResponse
    {
        $praktikum = Praktikum::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'mahasiswa_id' => 'required|integer|exists:mahasiswa,id',
        ]);

        $praktikum->mahasiswa()->detach($validated['mahasiswa_id']);

        return $this->successResponse(null, 'Mahasiswa berhasil dikeluarkan dari kelompok');
    }
}
