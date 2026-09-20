<?php

namespace App\Models;

use App\Enums\StatusAlatLog;
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

    public const STATUS = ['pengajuan', 'keluar', 'masuk', 'tambah', 'hapus', 'edit'];

    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class, 'inventaris', 'inventaris')->withTrashed();
    }

    protected function casts(): array
    {
        return [
            'nim_pic' => 'integer',
            'kondisi' => 'array',
            'status' => StatusAlatLog::class,
        ];
    }
}
