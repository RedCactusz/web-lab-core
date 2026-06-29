<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\NewsItem;
use App\Http\Requests\Admin\NewsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(): JsonResponse
    {
        $paginated = NewsItem::orderBy('date', 'desc')->paginate(15);
        return $this->successResponse($paginated, 'News retrieved');
    }

    public function store(NewsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $validated['slug'] = Str::slug($validated['title']);
        $news = NewsItem::create($validated);

        return $this->successResponse($news, 'News created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $news = NewsItem::findOrFail($id);
        return $this->successResponse($news, 'News retrieved');
    }

    public function update(NewsRequest $request, $id): JsonResponse
    {
        $news = NewsItem::findOrFail($id);

        $validated = $request->validated();

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
