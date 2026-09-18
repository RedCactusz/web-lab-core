<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PraktikumResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->praktikum_label,
            'semester' => $this->semester,
            'slug' => $this->praktikum_slug,
            'is_active' => $this->is_active,
            'plugs' => PraktikumJadwalResource::collection($this->whenLoaded('jadwal')),
        ];
    }
}
