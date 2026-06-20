<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $fillable = [
        'mahasiswa_id',
        'praktikum_id',
        'kelompok',
        'plug',
        'nilai_harian',
        'nilai_akhir',
    ];

    protected function casts(): array
    {
        return [
            'nilai_harian' => 'array',
            'nilai_akhir' => 'decimal:2',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function praktikum(): BelongsTo
    {
        return $this->belongsTo(Praktikum::class);
    }

    public static function hitungNilaiAkhir(array $nilaiHarian, int $praktikumId): float
    {
        $mingguList = PraktikumMinggu::byPraktikum($praktikumId)
            ->with('parameters')
            ->active()
            ->get();

        if ($mingguList->isEmpty() || empty($nilaiHarian)) {
            return 0;
        }

        $skorPresensi = [
            'Hadir' => 100,
            'Izin' => 50,
            'Sakit' => 50,
            'Alfa' => 0,
        ];

        $totalBobotNilai = 0;
        $totalMinggu = 0;

        foreach ($mingguList as $index => $minggu) {
            if (!isset($nilaiHarian[$index])) {
                continue;
            }

            $dataMinggu = $nilaiHarian[$index];
            $mingguScore = 0;

            foreach ($minggu->parameters as $param) {
                $nilaiParam = $dataMinggu[$param->nama] ?? null;

                if ($param->tipe === 'presensi') {
                    $skor = $skorPresensi[$nilaiParam] ?? 0;
                    $mingguScore += $skor * (float) $param->bobot;
                } else {
                    $nilaiNumeric = is_numeric($nilaiParam) ? (float) $nilaiParam : 0;
                    $normalized = $param->max_nilai > 0 ? ($nilaiNumeric / $param->max_nilai) * 100 : 0;
                    $mingguScore += $normalized * (float) $param->bobot;
                }
            }

            $totalBobotNilai += $mingguScore;
            $totalMinggu++;
        }

        if ($totalMinggu === 0) {
            return 0;
        }

        return round($totalBobotNilai / $totalMinggu, 2);
    }

    public function recalcNilaiAkhir(): void
    {
        if ($this->nilai_harian) {
            $this->nilai_akhir = self::hitungNilaiAkhir($this->nilai_harian, $this->praktikum_id);
        }
    }
}
