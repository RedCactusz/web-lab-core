<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NilaiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kelompok' => $this->kelompok,
            'plug' => $this->plug,
            'nilai_harian' => $this->nilai_harian,
            'nilai_akhir' => $this->nilai_akhir,
            'mahasiswa' => $this->whenLoaded('mahasiswa', function () {
                return [
                    'id' => $this->mahasiswa->id,
                    'nim' => $this->mahasiswa->nim,
                    'nama_lengkap' => $this->mahasiswa->nama_lengkap,
                ];
            }),
            'praktikum' => $this->whenLoaded('praktikum', function () {
                return [
                    'id' => $this->praktikum->id,
                    'kode' => $this->praktikum->kode,
                    'nama' => $this->praktikum->nama,
                    'slug' => $this->praktikum->slug,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
