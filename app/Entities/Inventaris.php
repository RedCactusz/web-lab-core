<?php

namespace App\Entities;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventaris extends Model
{
    use Auditable, HasFactory, SoftDeletes;

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
