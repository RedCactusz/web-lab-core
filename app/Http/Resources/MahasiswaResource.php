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
            'nama' => $this->nama,
            'nim' => $this->nim,
            'surel' => $this->surel,
            'angkatan' => $this->angkatan,
            'is_asisten' => $this->is_asisten,
            'pengampu' => $this->pengampu,
            'pengampu_plug' => $this->pengampu_plug,
            'role' => $this->role,
        ];
    }
}
