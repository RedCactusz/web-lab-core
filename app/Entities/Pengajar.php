<?php

namespace App\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengajar extends Model
{
    use HasFactory;

    protected $table = 'pengajar';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nip',
        'praktikum_slug', // Berisi string slug (misal: 'sutris1')
        'plug',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'plug' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function praktikum(): BelongsTo
    {
        // 'praktikum_slug' string merujuk ke kolom 'slug' milik tabel praktikum
        return $this->belongsTo(Praktikum::class, 'praktikum_slug', 'slug');
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'praktikum_id', 'praktikum_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}