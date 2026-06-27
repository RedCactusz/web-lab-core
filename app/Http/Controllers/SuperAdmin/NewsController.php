<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\NewsItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->successResponse(NewsItem::orderBy('date', 'desc')->get(), 'News retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'date' => 'required|date',
            'category' => 'required|string',
            'image' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $news = NewsItem::create($validated);

        return $this->successResponse($news, 'News created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $news = NewsItem::findOrFail($id);
        return $this->successResponse($news, 'News retrieved');
    }

    public function update(Request $request, $id): JsonResponse
    {
        $news = NewsItem::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'date' => 'required|date',
            'category' => 'required|string',
            'image' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        if (isset($validated['title']) && $validated['title'] !== $news->title) {
            $validated['slug'] = Str::slug($validated['title']);
        } else {
            unset($validated['slug']);
        }

        $news->update($validated);

        return $this->successResponse($news, 'News updated successfully');
    }

    public function destroy($id): JsonResponse
    {
        $news = NewsItem::findOrFail($id);
        $news->delete();

        return $this->successResponse(null, 'Berita berhasil dihapus');
    }
}
