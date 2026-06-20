<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PraktikumJadwal extends Model
{
    use HasFactory;

    protected $table = 'praktikum_jadwal';

    protected $fillable = [
        'praktikum_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'ruangan',
        'topik',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function praktikum(): BelongsTo
    {
        return $this->belongsTo(Praktikum::class);
    }

    public function scopeByPraktikum($query, int $praktikumId)
    {
        return $query->where('praktikum_id', $praktikumId)->orderBy('tanggal');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('tanggal', '>=', now()->toDateString());
    }
}
