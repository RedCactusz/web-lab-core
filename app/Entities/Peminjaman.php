<?php

namespace App\Entities;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peminjaman extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    protected $table = 'peminjaman';

    protected $fillable = [
        'mahasiswa_id',
        'tanggal_pengajuan',
        'nama_alat',
        'jumlah',
        'tanggal_pinjam',
        'jam_pinjam',
        'tanggal_kembali',
        'jam_kembali',
        'status',
        'keterangan',
        'keperluan',
        'alasan_lainnya',
        'items',
        'revised_items',
        'revisi_catatan',
        'pengembalian_catatan',
        'pengembalian_items',
        'tanggal_dikembalikan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'date',
            'tanggal_pinjam' => 'date',
            'tanggal_kembali' => 'date',
            'tanggal_dikembalikan' => 'date',
            'items' => 'array',
            'revised_items' => 'array',
            'pengembalian_items' => 'array',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'approved']);
    }
}
