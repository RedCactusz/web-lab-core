<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\MahasiswaResource;
use App\Models\Mahasiswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'angkatan' => ['nullable', 'integer'],
            'role' => ['nullable', 'in:asisten,mahasiswa'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $mahasiswa = Mahasiswa::query()
            ->when($validated['search'] ?? null, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                });
            })
            ->when($validated['angkatan'] ?? null, fn ($query, int $angkatan) => $query->where('angkatan', $angkatan))
            ->when(($validated['role'] ?? null) === 'asisten', fn ($query) => $query
                ->where('is_asisten', true)
                ->where('is_active_asisten', true))
            ->when(($validated['role'] ?? null) === 'mahasiswa', fn ($query) => $query
                ->where(function ($query) {
                    $query->where('is_asisten', false)->orWhere('is_active_asisten', false);
                }))
            ->orderBy('nama')
            ->paginate(perPage: $validated['per_page'] ?? 15, page: $validated['page'] ?? null);

        return response()->json([
            'data' => MahasiswaResource::collection($mahasiswa->items()),
            'meta' => [
                'current_page' => $mahasiswa->currentPage(),
                'last_page' => $mahasiswa->lastPage(),
                'per_page' => $mahasiswa->perPage(),
                'total' => $mahasiswa->total(),
            ],
        ]);
    }
}
