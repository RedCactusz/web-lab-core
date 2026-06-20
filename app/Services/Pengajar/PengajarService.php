<?php

namespace App\Services\Pengajar;

use App\Entities\Mahasiswa;
use App\Entities\Nilai;
use App\Entities\Pengajar;
use App\Entities\Praktikum;
use Illuminate\Support\Collection;

class PengajarService
{
    public function getMyProfile(Pengajar $pengajar): Pengajar
    {
        return $pengajar->load(['user', 'praktikum']);
    }

    public function getStudentsByPraktikum(int $praktikumId, array $plugs = null): Collection
    {
        $query = Mahasiswa::with(['nilai' => function ($q) use ($praktikumId) {
            $q->where('praktikum_id', $praktikumId);
        }])
        ->whereHas('praktikum', function ($q) use ($praktikumId, $plugs) {
            $q->where('praktikum.id', $praktikumId);
            if ($plugs) {
                $q->wherePivotIn('plug', $plugs);
            }
        });

        return $query->active()->orderBy('nim')->get();
    }

    public function getStudentByNim(int $praktikumId, string $nim): ?Mahasiswa
    {
        return Mahasiswa::with(['nilai' => function ($q) use ($praktikumId) {
            $q->where('praktikum_id', $praktikumId);
        }])
        ->where('nim', $nim)
        ->first();
    }

    public function updateStudentGrade(int $praktikumId, string $nim, array $gradeData): ?Nilai
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if (!$mahasiswa) {
            return null;
        }

        $nilai = Nilai::firstOrCreate(
            [
                'mahasiswa_id' => $mahasiswa->id,
                'praktikum_id' => $praktikumId,
            ],
            [
                'kelompok' => $gradeData['kelompok'] ?? null,
                'plug' => $gradeData['plug'] ?? null,
            ]
        );

        if (isset($gradeData['kelompok'])) {
            $nilai->kelompok = $gradeData['kelompok'];
        }
        if (isset($gradeData['plug'])) {
            $nilai->plug = $gradeData['plug'];
        }
        if (isset($gradeData['nilai_harian'])) {
            $nilai->nilai_harian = $gradeData['nilai_harian'];
            $nilai->recalcNilaiAkhir();
        }

        $nilai->save();

        return $nilai->load(['mahasiswa', 'praktikum']);
    }

    public function getStats(int $praktikumId): array
    {
        $totalMahasiswa = Nilai::where('praktikum_id', $praktikumId)->count();

        $nilaiAkhir = Nilai::where('praktikum_id', $praktikumId)
            ->whereNotNull('nilai_akhir')
            ->pluck('nilai_akhir');

        return [
            'total_mahasiswa' => $totalMahasiswa,
            'rata_rata' => $nilaiAkhir->count() > 0 ? round($nilaiAkhir->avg(), 2) : 0,
            'nilai_tertinggi' => $nilaiAkhir->count() > 0 ? round($nilaiAkhir->max(), 2) : 0,
            'nilai_terendah' => $nilaiAkhir->count() > 0 ? round($nilaiAkhir->min(), 2) : 0,
        ];
    }
}
