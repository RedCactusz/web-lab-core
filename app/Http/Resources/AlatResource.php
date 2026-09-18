<?php

namespace App\Http\Resources;

use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'inventaris' => $this->inventaris,
            'nama_alat' => $this->nama_alat,
            'merk' => $this->merk,
            'tipe' => $this->tipe,
            'serial_number' => $this->serial_number,
            'jumlah' => $this->jumlah,
            'kondisi' => $this->kondisi,
            'kondisi_ringkas' => collect(Alat::KONDISI_STATUSES)
                ->mapWithKeys(fn (string $status) => [
                    $status => (int) collect($this->kondisi ?? [])
                        ->where('status', $status)
                        ->sum('jumlah'),
                ])
                ->all(),
            'lokasi_penyimpanan' => $this->lokasi_penyimpanan,
            'ketersediaan' => $this->ketersediaan,
        ];
    }
}
