<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    use HasFactory;

    protected $table = 'inventaris';

    protected $fillable = [
        'kode_alat',
        'nama',
        'kategori',
        'merk',
        'tipe',
        'kondisi',
        'jumlah',
        'lokasi',
        'keterangan',
        'foto',
    ];

    protected function casts(): array
    {
        return [
            'foto' => 'array',
            'jumlah' => 'integer',
        ];
    }

    public function scopeBaik($query)
    {
        return $query->where('kondisi', 'baik');
    }

    public function scopeRusak($query)
    {
        return $query->whereIn('kondisi', ['rusak_ringan', 'rusak_berat']);
    }
}
