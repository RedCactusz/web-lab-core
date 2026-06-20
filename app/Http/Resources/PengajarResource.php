<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengajarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_lengkap' => $this->nama_lengkap,
            'nip' => $this->nip,
            'plug' => $this->plug,
            'is_active' => $this->is_active,
            'praktikum' => $this->whenLoaded('praktikum', function () {
                return [
                    'id' => $this->praktikum->id,
                    'kode' => $this->praktikum->kode,
                    'nama' => $this->praktikum->nama,
                    'slug' => $this->praktikum->slug,
                ];
            }),
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
