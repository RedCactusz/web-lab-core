<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeminjamanAlatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'alat_id' => $this->alat_id,
            'inventaris' => $this->inventaris,
            'nama_alat' => $this->alat->nama_alat,
            'merk' => $this->alat->merk,
            'tipe' => $this->alat->tipe,
            'jumlah' => $this->jumlah,
        ];
    }
}
