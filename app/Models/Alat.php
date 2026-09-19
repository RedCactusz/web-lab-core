<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'inventaris',
    'nama_alat',
    'merk',
    'tipe',
    'serial_number',
    'jumlah',
    'kondisi',
    'lokasi_penyimpanan',
    'ketersediaan',
])]
class Alat extends Model
{
    use SoftDeletes;

    protected $table = 'alat';

    public const KONDISI_STATUSES = ['baik', 'rusak_ringan', 'rusak_berat', 'maintenance'];

    public const KETERSEDIAAN_STATUSES = ['tersedia', 'dipinjam', 'perbaikan'];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'kondisi' => 'array',
        ];
    }
}
