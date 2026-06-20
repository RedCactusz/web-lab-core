<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PraktikumMinggu extends Model
{
    use HasFactory;

    protected $table = 'praktikum_minggu';

    protected $fillable = [
        'praktikum_id',
        'minggu_ke',
        'topik',
        'tanggal',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'tanggal' => 'date',
        ];
    }

    public function praktikum(): BelongsTo
    {
        return $this->belongsTo(Praktikum::class);
    }

    public function parameters(): HasMany
    {
        return $this->hasMany(PenilaianParameter::class, 'praktikum_minggu_id')->orderBy('urutan');
    }

    public function scopeByPraktikum($query, int $praktikumId)
    {
        return $query->where('praktikum_id', $praktikumId)->orderBy('minggu_ke');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
