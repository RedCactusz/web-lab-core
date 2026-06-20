<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'date' => $this->date->format('Y-m-d'),
            'category' => $this->category,
            'image' => $this->image,
            'slug' => $this->slug,
            'created_at' => $this->created_at,
        ];
    }
}
