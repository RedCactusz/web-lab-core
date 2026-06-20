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
            'kode' => $this->kode,
            'nama' => $this->nama,
            'slug' => $this->slug,
            'deskripsi' => $this->deskripsi,
            'is_active' => $this->is_active,
            'pengajar_count' => $this->whenCounted('pengajar'),
            'mahasiswa_count' => $this->whenCounted('mahasiswa'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
