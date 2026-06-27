<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\AgendaItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AgendaController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->successResponse(AgendaItem::orderBy('date', 'asc')->get(), 'Agenda retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $agenda = AgendaItem::create($validated);

        return $this->successResponse($agenda, 'Agenda created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $agenda = AgendaItem::findOrFail($id);
        return $this->successResponse($agenda, 'Agenda retrieved');
    }

    public function update(Request $request, $id): JsonResponse
    {
        $agenda = AgendaItem::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

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
