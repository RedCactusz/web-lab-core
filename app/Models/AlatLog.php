<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'id_log',
    'keperluan',
    'nim_pic',
    'nama_pic',
    'inventaris',
    'kondisi',
    'status',
])]
class AlatLog extends Model
{
    protected $table = 'alat_log';

    public const STATUS = ['keluar', 'masuk'];

    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class, 'inventaris', 'inventaris');
    }

    protected function casts(): array
    {
        return [
            'nim_pic' => 'integer',
            'kondisi' => 'array',
        ];
    }
}
