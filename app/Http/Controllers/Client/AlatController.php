<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlatLogResource;
use App\Http\Resources\AlatResource;
use App\Models\Alat;
use App\Models\AlatLog;
use App\Services\AlatLogService;
use App\Services\CrudService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlatController extends Controller
{
    public function __construct(
        private readonly AlatLogService $alatLog,
        private readonly CrudService $crud,
    ) {
    }

    public function index(): JsonResponse
    {
        $alat = Alat::query()->orderBy('nama_alat')->get();

        return response()->json(['data' => AlatResource::collection($alat)]);
    }

    public function pinjam(Request $request, Alat $alat): JsonResponse
    {
        $user = $request->user();

        $result = DB::transaction(function () use ($alat, $user): ?AlatLog {
            $updated = Alat::query()
                ->whereKey($alat->id)
                ->where('ketersediaan', 'tersedia')
                ->update(['ketersediaan' => 'dipinjam']);

            if ($updated === 0) {
                return null;
            }

            return $this->alatLog->catat(
                attributes: [
                    'keperluan' => 'pjm',
                    'nim_pic' => $user->nim,
                    'nama_pic' => null,
                    'inventaris' => $alat->inventaris,
                    'kondisi' => $alat->kondisi,
                    'status' => 'keluar',
                ],
                peminjam: 'mhs',
            );
        });

        if ($result === null) {
            return response()->json(['message' => 'Alat sedang tidak tersedia.'], 422);
        }

        return response()->json(['message' => 'Alat berhasil dipinjam.'], 201);
    }

    public function kembali(Request $request, Alat $alat): JsonResponse
    {
        $user = $request->user();

        $result = DB::transaction(function () use ($alat, $user): ?AlatLog {
            $updated = Alat::query()
                ->whereKey($alat->id)
                ->where('ketersediaan', 'dipinjam')
                ->update(['ketersediaan' => 'tersedia']);

            if ($updated === 0) {
                return null;
            }

            return $this->alatLog->catat(
                attributes: [
                    'keperluan' => 'pjm',
                    'nim_pic' => $user->nim,
                    'nama_pic' => null,
                    'inventaris' => $alat->inventaris,
                    'kondisi' => $alat->kondisi,
                    'status' => 'masuk',
                ],
                peminjam: 'mhs',
            );
        });

        if ($result === null) {
            return response()->json(['message' => 'Alat tidak sedang dipinjam.'], 422);
        }

        return response()->json(['message' => 'Alat berhasil dikembalikan.']);
    }

    public function riwayat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $log = AlatLog::query()
            ->with(['alat' => fn ($query) => $query->withTrashed()])
            ->where('nim_pic', $request->user()->nim)
            ->where('keperluan', 'pjm')
            ->latest()
            ->paginate(perPage: $validated['per_page'] ?? 15, page: $validated['page'] ?? null);

        return $this->crud->paginated($log, AlatLogResource::class);
    }
}
