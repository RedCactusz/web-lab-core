<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgendaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'date' => $this->date->format('Y-m-d'),
            'time' => $this->time,
            'location' => $this->location,
            'description' => $this->description,
            'created_at' => $this->created_at,
        ];
    }
}
