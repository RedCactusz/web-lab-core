<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaianParameter extends Model
{
    use HasFactory;

    protected $table = 'penilaian_parameter';

    protected $fillable = [
        'praktikum_minggu_id',
        'nama',
        'bobot',
        'tipe',
        'max_nilai',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'bobot' => 'decimal:3',
            'max_nilai' => 'integer',
            'urutan' => 'integer',
        ];
    }

    public function minggu(): BelongsTo
    {
        return $this->belongsTo(PraktikumMinggu::class, 'praktikum_minggu_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('nama');
    }
}
