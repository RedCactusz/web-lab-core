<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'peminjaman_id',
    'alat_id',
    'inventaris',
    'jumlah',
])]
class PeminjamanAlat extends Model
{
    protected $table = 'peminjaman_alat';

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class)->withTrashed();
    }

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
        ];
    }
}
