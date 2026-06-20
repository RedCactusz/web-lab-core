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
            'tanggal_pengajuan' => $this->tanggal_pengajuan->format('Y-m-d'),
            'nama_alat' => $this->nama_alat,
            'jumlah' => $this->jumlah,
            'tanggal_pinjam' => $this->tanggal_pinjam?->format('Y-m-d'),
            'tanggal_kembali' => $this->tanggal_kembali?->format('Y-m-d'),
            'status' => $this->status,
            'keterangan' => $this->keterangan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
