<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PeminjamanResource;
use App\Models\Peminjaman;
use App\Services\CrudService;
use App\Services\PeminjamanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function __construct(
        private readonly PeminjamanService $peminjaman,
        private readonly CrudService $crud,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:'.implode(',', Peminjaman::STATUS)],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $peminjaman = Peminjaman::query()
            ->with('items.alat')
            ->when($validated['search'] ?? null, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nama', 'ilike', "%{$search}%")
                        ->orWhere('nim', 'ilike', "%{$search}%")
                        ->orWhere('keperluan', 'ilike', "%{$search}%")
                        ->orWhereHas('items', fn ($query) => $query
                            ->where('inventaris', 'ilike', "%{$search}%"));
                });
            })
            ->when($validated['status'] ?? null, fn ($query, string $status) => $query
                ->where('status', $status))
            ->latest()
            ->paginate(perPage: $validated['per_page'] ?? 15, page: $validated['page'] ?? null);

        return $this->crud->paginated($peminjaman, PeminjamanResource::class);
    }

    public function setujui(Request $request, Peminjaman $peminjaman): JsonResponse
    {
        if ($peminjaman->status !== 'pending') {
            return response()->json(['message' => 'Pengajuan sudah diproses.'], 422);
        }

        $this->peminjaman->setujui($peminjaman, approvedBy: $request->user()->nama);

        return response()->json(new PeminjamanResource($peminjaman->fresh(['items.alat'])));
    }

    public function tolak(Request $request, Peminjaman $peminjaman): JsonResponse
    {
        if ($peminjaman->status !== 'pending') {
            return response()->json(['message' => 'Pengajuan sudah diproses.'], 422);
        }

        $validated = $request->validate([
            'catatan' => ['required', 'string', 'max:500'],
        ]);

        $this->peminjaman->tolak($peminjaman, $validated['catatan']);

        return response()->json(new PeminjamanResource($peminjaman->fresh(['items.alat'])));
    }

    public function kembalikan(Request $request, Peminjaman $peminjaman): JsonResponse
    {
        if ($peminjaman->status !== 'disetujui' || $peminjaman->returned_at !== null) {
            return response()->json(['message' => 'Peminjaman ini tidak sedang berjalan.'], 422);
        }

        $this->peminjaman->kembalikan($peminjaman);

        return response()->json(new PeminjamanResource($peminjaman->fresh(['items.alat'])));
    }
}
