<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlatResource;
use App\Models\Alat;
use Illuminate\Http\JsonResponse;

class AlatController extends Controller
{
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
}
