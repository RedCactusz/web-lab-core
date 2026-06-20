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
        return response()->json(AgendaItem::orderBy('date', 'asc')->get());
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

        return response()->json($agenda, 201);
    }

    public function show($id): JsonResponse
    {
        $agenda = AgendaItem::findOrFail($id);
        return response()->json($agenda);
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

        return response()->json($agenda);
    }

    public function destroy($id): JsonResponse
    {
        $agenda = AgendaItem::findOrFail($id);
        $agenda->delete();

        return response()->json(['message' => 'Agenda berhasil dihapus']);
    }
}
