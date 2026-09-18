<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'pertemuan_id',
    'mahasiswa_id',
    'nilai',
])]
class PraktikumNilai extends Model
{
    protected $table = 'praktikum_nilai';

    protected function casts(): array
    {
        return [
            'nilai' => 'array',
        ];
    }

    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(PraktikumPertemuan::class, 'pertemuan_id');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
