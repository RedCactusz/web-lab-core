<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlatLogResource;
use App\Http\Resources\AlatResource;
use App\Models\Alat;
use App\Models\AlatLog;
use App\Services\CrudService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    public function __construct(
        private readonly CrudService $crud,
    ) {
    }

    public function index(): JsonResponse
    {
        $alat = Alat::query()
            ->orderBy('nama_alat')
            ->orderBy('inventaris')
            ->get()
            ->groupBy('nama_alat')
            ->values()
            ->map(fn ($group) => [
                'nama_alat' => $group->first()->nama_alat,
                'jumlah_unit' => $group->count(),
                'jumlah_tersedia' => $group->where('ketersediaan', 'tersedia')->count(),
                'items' => AlatResource::collection($group->values()),
            ]);

        return response()->json(['data' => $alat]);
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
