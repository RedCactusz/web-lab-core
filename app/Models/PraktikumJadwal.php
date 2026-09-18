<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'praktikum_id',
    'plug',
    'hari',
    'jam_mulai',
    'jam_selesai',
    'is_active',
])]

class PraktikumJadwal extends Model
{
    protected $table = 'praktikum_jadwal';

    protected function casts(): array
    {
        return [
            'plug' => 'string',
            'hari' => 'string',
            'jam_mulai' => 'datetime:H:i',
            'jam_selesai' => 'datetime:H:i',
            'is_active' => 'boolean',
        ];
    }

    public function praktikum(): BelongsTo
    {
        return $this->belongsTo(Praktikum::class);
    }
}
