<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\AgendaResource;
use App\Http\Resources\GalleryResource;
use App\Http\Resources\KerjasamaResource;
use App\Http\Resources\NewsResource;
use App\Http\Resources\PartnerResource;
use App\Services\Public\PublicContentService;
use Illuminate\Http\JsonResponse;

class PublicContentController extends Controller
{
    public function __construct(
        protected PublicContentService $publicContentService
    ) {}

    public function gallery(): JsonResponse
    {
        $items = $this->publicContentService->getGallery();

        return $this->successResponse(GalleryResource::collection($items), 'Gallery retrieved');
    }

    public function news(): JsonResponse
    {
        $items = $this->publicContentService->getNews();

        return $this->successResponse(NewsResource::collection($items), 'News retrieved');
    }

    public function agenda(): JsonResponse
    {
        $items = $this->publicContentService->getAgenda();

        return $this->successResponse(AgendaResource::collection($items), 'Agenda retrieved');
    }

    public function partners(): JsonResponse
    {
        $items = $this->publicContentService->getPartners();

        return $this->successResponse(PartnerResource::collection($items), 'Partners retrieved');
    }

    public function kerjasama(): JsonResponse
    {
        $items = $this->publicContentService->getKerjasama();

        return $this->successResponse(KerjasamaResource::collection($items), 'Kerjasama retrieved');
    }
}
