<?php

namespace App\Services;

use App\Models\AlatLog;
use Illuminate\Support\Facades\DB;

class AlatLogService
{
    /**
     * Catat satu event pergerakan alat.
     *
     * @param  array<string, mixed>  $attributes  kolom alat_log kecuali id_log
     */
    public function catat(array $attributes, string $peminjam, ?int $refId = null): AlatLog
    {
        return AlatLog::create([
            ...$attributes,
            'id_log' => $this->generateIdLog($attributes['keperluan'], $peminjam, $refId),
        ]);
    }

    /**
     * Format: {keperluan}[:{ref}]/{peminjam}/{nomor urut event per bulan}/{bulan}/{tahun}
     * Contoh: prk:5/mhs/000001/11/2026, sw/um/000002/11/2026, rm/sys/000003/11/2026
     */
    public function generateIdLog(string $keperluan, string $peminjam, ?int $refId = null): string
    {
        return DB::transaction(function () use ($keperluan, $peminjam, $refId) {
            $now = now();

            $nomor = AlatLog::query()
                ->whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->distinct()
                ->count('id_log') + 1;

            $keperluan = $refId !== null ? "{$keperluan}:{$refId}" : $keperluan;

            return sprintf('%s/%s/%06d/%02d/%d', $keperluan, $peminjam, $nomor, $now->month, $now->year);
        });
    }
}
