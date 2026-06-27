<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\GalleryItem;
use App\Http\Requests\Admin\GalleryRequest;
use Illuminate\Http\JsonResponse;

class GalleryController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->successResponse(GalleryItem::orderBy('created_at', 'desc')->get(), 'Gallery retrieved');
    }

    public function store(GalleryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $gallery = GalleryItem::create($validated);

        return $this->successResponse($gallery, 'Gallery created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $gallery = GalleryItem::findOrFail($id);
        return $this->successResponse($gallery, 'Gallery retrieved');
    }

    public function update(GalleryRequest $request, $id): JsonResponse
    {
        $gallery = GalleryItem::findOrFail($id);

        $validated = $request->validated();

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
