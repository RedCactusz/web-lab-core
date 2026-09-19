<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\PeminjamanResource;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Services\CrudService;
use App\Services\PeminjamanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $peminjaman = Peminjaman::query()
            ->with('items.alat')
            ->where('nim', $request->user()->nim)
            ->latest()
            ->paginate(perPage: $validated['per_page'] ?? 15, page: $validated['page'] ?? null);

        return $this->crud->paginated($peminjaman, PeminjamanResource::class);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'keperluan' => ['required', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.alat_id' => ['required', 'integer', 'exists:alat,id'],
            'items.*.jumlah' => ['required', 'integer', 'min:1'],
        ]);

        $this->validasiStok($validated['items']);

        $user = $request->user();
        $peminjaman = $this->peminjaman->ajukan(
            nim: $user->nim,
            nama: $user->nama,
            keperluan: $validated['keperluan'],
            items: $validated['items'],
        );

        return response()->json(new PeminjamanResource($peminjaman->load('items.alat')), 201);
    }

    /**
     * Jumlah yang diminta tidak boleh melebihi total unit alat.
     *
     * @param  array<int, array{alat_id: int, jumlah: int}>  $items
     */
    private function validasiStok(array $items): void
    {
        $errors = [];

        foreach ($items as $index => $item) {
            $alat = Alat::query()->find($item['alat_id']);

            if ($alat !== null && $item['jumlah'] > $alat->jumlah) {
                $errors["items.{$index}.jumlah"] = ["Jumlah melebihi total unit {$alat->nama_alat} ({$alat->jumlah})."];
            }
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }
}
