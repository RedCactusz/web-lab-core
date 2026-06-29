<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\Kerjasama;
use App\Http\Requests\Admin\KerjasamaRequest;
use Illuminate\Http\JsonResponse;

class KerjasamaController extends Controller
{
    public function index(): JsonResponse
    {
        $paginated = Kerjasama::orderBy('created_at', 'desc')->paginate(15);
        return $this->successResponse($paginated, 'Kerjasama retrieved');
    }

    public function store(KerjasamaRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $kerjasama = Kerjasama::create($validated);

        return $this->successResponse($kerjasama, 'Kerjasama created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $kerjasama = Kerjasama::findOrFail($id);
        return $this->successResponse($kerjasama, 'Kerjasama retrieved');
    }

    public function update(KerjasamaRequest $request, $id): JsonResponse
    {
        $kerjasama = Kerjasama::findOrFail($id);

        $validated = $request->validated();

        $kerjasama->update($validated);

        return $this->successResponse($kerjasama, 'Kerjasama updated successfully');
    }

    public function destroy($id): JsonResponse
    {
        $kerjasama = Kerjasama::findOrFail($id);
        $kerjasama->delete();

        return $this->successResponse(null, 'Kerjasama berhasil dihapus');
    }
}
