<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\AgendaItem;
use App\Http\Requests\Admin\AgendaRequest;
use Illuminate\Http\JsonResponse;

class AgendaController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->successResponse(AgendaItem::orderBy('date', 'asc')->get(), 'Agenda retrieved');
    }

    public function store(AgendaRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $agenda = AgendaItem::create($validated);

        return $this->successResponse($agenda, 'Agenda created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $agenda = AgendaItem::findOrFail($id);
        return $this->successResponse($agenda, 'Agenda retrieved');
    }

    public function update(AgendaRequest $request, $id): JsonResponse
    {
        $agenda = AgendaItem::findOrFail($id);

        $validated = $request->validated();

        $agenda->update($validated);

        return $this->successResponse($agenda, 'Agenda updated successfully');
    }

    public function destroy($id): JsonResponse
    {
        $agenda = AgendaItem::findOrFail($id);
        $agenda->delete();

        return $this->successResponse(null, 'Agenda berhasil dihapus');
    }
}
