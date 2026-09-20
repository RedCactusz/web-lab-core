<?php

namespace App\Services;

use App\Enums\StatusAlatLog;
use App\Enums\TipePic;
use App\Models\AlatLog;
use Illuminate\Support\Facades\DB;

class AlatLogService
{
    /**
     * Catat satu event pergerakan/perubahan alat.
     *
     * @param  array<string, mixed>  $attributes  kolom alat_log kecuali id_log;
     *                                            'keperluan' berisi terjemahan kode (mis. 'sewa', '-'),
     *                                            'status' berisi StatusAlatLog
     */
    public function catat(string $kode, array $attributes, TipePic $peminjam): AlatLog
    {
        /** @var StatusAlatLog $status */
        $status = $attributes['status'];

        return AlatLog::create([
            ...$attributes,
            'id_log' => $this->generateIdLog($kode, $status, $peminjam),
        ]);
    }

    /**
     * Format: {kode}:{status}/{tipe_pic}/{nomor urut event per bulan}/{bulan}/{tahun}
     * Contoh: swa:keluar/mhs/0000001/09/2026, inv:hapus/sys/0000002/09/2026
     */
    public function generateIdLog(string $kode, StatusAlatLog $status, TipePic $peminjam): string
    {
        return DB::transaction(function () use ($kode, $status, $peminjam) {
            $now = now();

            $nomor = AlatLog::query()
                ->whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->distinct()
                ->count('id_log') + 1;

            return sprintf(
                '%s:%s/%s/%07d/%02d/%d',
                $kode,
                $status->value,
                $peminjam->value,
                $nomor,
                $now->month,
                $now->year,
            );
        });
    }
}
