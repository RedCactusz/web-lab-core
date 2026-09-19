<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlatLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_log' => $this->id_log,
            'keperluan' => $this->keperluan,
            'nim_pic' => $this->nim_pic,
            'nama_pic' => $this->nama_pic,
            'inventaris' => $this->inventaris,
            'nama_alat' => $this->alat->nama_alat,
            'kondisi' => $this->kondisi,
            'status' => $this->status,
            'waktu' => $this->created_at->toIso8601String(),
        ];
    }
}
