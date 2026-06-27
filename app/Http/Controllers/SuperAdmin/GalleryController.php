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
        return $this->successResponse(GalleryItem::orderBy('created_at', 'desc')->get(), 'Gallery retrieved');
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

        return $this->successResponse($gallery, 'Gallery created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $gallery = GalleryItem::findOrFail($id);
        return $this->successResponse($gallery, 'Gallery retrieved');
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

        return $this->successResponse($gallery, 'Gallery updated successfully');
    }

    public function destroy($id): JsonResponse
    {
        $gallery = GalleryItem::findOrFail($id);
        $gallery->delete();

        return $this->successResponse(null, 'Galeri berhasil dihapus');
    }
}
