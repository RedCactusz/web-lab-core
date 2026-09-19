<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeminjamanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nim' => $this->nim,
            'nama' => $this->nama,
            'keperluan' => $this->keperluan,
            'status' => $this->status,
            'catatan' => $this->catatan,
            'approved_by' => $this->approved_by,
            'approved_at' => $this->approved_at?->toIso8601String(),
            'returned_at' => $this->returned_at?->toIso8601String(),
            'dibuat_pada' => $this->created_at->toIso8601String(),
            'items' => PeminjamanAlatResource::collection($this->whenLoaded('items')),
        ];
    }
}
