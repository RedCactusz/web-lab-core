<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GalleryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(GalleryItem::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'image' => 'required|string',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $gallery = GalleryItem::create($validated);

        return response()->json($gallery, 201);
    }

    public function show($id): JsonResponse
    {
        $gallery = GalleryItem::findOrFail($id);
        return response()->json($gallery);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $gallery = GalleryItem::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string',
            'image' => 'required|string',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $gallery->update($validated);

        return response()->json($gallery);
    }

    public function destroy($id): JsonResponse
    {
        $gallery = GalleryItem::findOrFail($id);
        $gallery->delete();

        return response()->json(['message' => 'Galeri berhasil dihapus']);
    }
}
