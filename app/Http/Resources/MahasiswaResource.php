<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MahasiswaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nim' => $this->nim,
            'nama_lengkap' => $this->nama_lengkap,
            'angkatan' => $this->angkatan,
            'is_active' => $this->is_active,
            'praktikum' => $this->whenLoaded('praktikum', function () {
                return $this->praktikum->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'kode' => $p->kode,
                        'nama' => $p->nama,
                        'slug' => $p->slug,
                        'pivot' => [
                            'kelompok' => $p->pivot->kelompok,
                            'plug' => $p->pivot->plug,
                        ],
                    ];
                });
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
