<?php

namespace App\Entities;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Praktikum extends Model
{
    use Auditable, HasFactory;

    protected $table = 'praktikum';

    protected $fillable = [
        'kode',
        'nama',
        'slug',
        'deskripsi',
        'is_active',
        'jumlah_plug',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'jumlah_plug' => 'integer',
        ];
    }

    public function pengajar(): HasMany
    {
        return $this->hasMany(Pengajar::class, 'praktikum_slug', 'slug');
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    public function mahasiswa(): BelongsToMany
    {
        return $this->belongsToMany(Mahasiswa::class, 'mahasiswa_praktikum')
            ->withPivot('kelompok', 'plug')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
