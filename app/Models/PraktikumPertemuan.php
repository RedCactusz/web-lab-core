<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'praktikum_id',
    'nomor',
    'topik',
    'tanggal',
    'bobot',
    'parameter',
])]
class PraktikumPertemuan extends Model
{
    protected $table = 'praktikum_pertemuan';

    protected function casts(): array
    {
        return [
            'nomor' => 'integer',
            'tanggal' => 'date',
            'bobot' => 'integer',
            'parameter' => 'array',
        ];
    }

    public function praktikum(): BelongsTo
    {
        return $this->belongsTo(Praktikum::class);
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(PraktikumNilai::class, 'pertemuan_id');
    }
}
