<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlatLogResource;
use App\Models\AlatLog;
use App\Services\CrudService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlatLogController extends Controller
{
    public function __construct(private readonly CrudService $crud)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:'.implode(',', AlatLog::STATUS)],
            'keperluan' => ['nullable', 'string', 'max:50'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $log = AlatLog::query()
            ->with(['alat' => fn ($query) => $query->withTrashed()])
            ->when($validated['search'] ?? null, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('id_log', 'ilike', "%{$search}%")
                        ->orWhere('inventaris', 'ilike', "%{$search}%")
                        ->orWhere('nim_pic', 'like', "{$search}%")
                        ->orWhere('nama_pic', 'ilike', "%{$search}%");
                });
            })
            ->when($validated['status'] ?? null, fn ($query, string $status) => $query
                ->where('status', $status))
            ->when($validated['keperluan'] ?? null, fn ($query, string $keperluan) => $query
                ->where('keperluan', $keperluan))
            ->latest()
            ->paginate(perPage: $validated['per_page'] ?? 15, page: $validated['page'] ?? null);

        return $this->crud->paginated($log, AlatLogResource::class);
    }
}
