<?php

namespace App\Services\Public;

use App\Entities\AgendaItem;
use App\Entities\GalleryItem;
use App\Entities\Kerjasama;
use App\Entities\NewsItem;
use App\Entities\OrganizationStructure;
use App\Entities\Partner;
use Illuminate\Support\Collection;

class PublicContentService
{
    public function getGallery(): Collection
    {
        return GalleryItem::published()->ordered()->get();
    }

    public function getNews(int $limit = 10): Collection
    {
        return NewsItem::published()->recent($limit)->get();
    }

    public function getAgenda(): Collection
    {
        return AgendaItem::published()->upcoming()->get();
    }

    public function getPartners(): Collection
    {
        return Partner::published()->ordered()->get();
    }

    public function getKerjasama(): Collection
    {
        return Kerjasama::published()->orderBy('created_at', 'desc')->get();
    }

    public function getStructure(): Collection
    {
        return OrganizationStructure::published()->ordered()->get();
    }
}
