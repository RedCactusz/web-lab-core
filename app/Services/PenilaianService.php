<?php

namespace App\Services;

use App\Entities\Mahasiswa;
use App\Entities\Nilai;
use App\Entities\PenilaianParameter;
use App\Entities\Praktikum;
use App\Entities\PraktikumJadwal;
use App\Entities\PraktikumMinggu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PenilaianService
{
    const TOTAL_MINGGU = 14;
    const BOBOT_PRESENSI_DEFAULT = 0.10;

    public function generateMinggu(int $praktikumId, int $total = self::TOTAL_MINGGU): Collection
    {
        $praktikum = Praktikum::findOrFail($praktikumId);

        $existing = PraktikumMinggu::byPraktikum($praktikumId)->count();
        if ($existing > 0) {
            throw new \Exception('Minggu sudah pernah di-generate untuk praktikum ini');
        }

        $mingguList = collect();

        DB::transaction(function () use ($praktikumId, $total, $mingguList) {
            for ($i = 1; $i <= $total; $i++) {
                $minggu = PraktikumMinggu::create([
                    'praktikum_id' => $praktikumId,
                    'minggu_ke' => $i,
                    'is_active' => true,
                ]);

                PenilaianParameter::create([
                    'praktikum_minggu_id' => $minggu->id,
                    'nama' => 'presensi',
                    'bobot' => self::BOBOT_PRESENSI_DEFAULT,
                    'tipe' => 'presensi',
                    'max_nilai' => 100,
                    'urutan' => 0,
                ]);

                $mingguList->push($minggu->load('parameters'));
            }
        });

        return $mingguList;
    }

    public function addParameter(int $mingguId, array $data): PenilaianParameter
    {
        $minggu = PraktikumMinggu::with('parameters')->findOrFail($mingguId);

        $totalBobot = $minggu->parameters->sum('bobot');
        $bobotBaru = (float) $data['bobot'];

        if (($totalBobot + $bobotBaru) > 1.00) {
            throw new \Exception('Total bobot melebihi 100%');
        }

        $existingParam = $minggu->parameters->firstWhere('nama', $data['nama']);
        if ($existingParam) {
            throw new \Exception('Parameter dengan nama tersebut sudah ada');
        }

        $urutan = $minggu->parameters->max('urutan') + 1;

        return PenilaianParameter::create([
            'praktikum_minggu_id' => $mingguId,
            'nama' => $data['nama'],
            'bobot' => $data['bobot'],
            'tipe' => $data['tipe'] ?? 'numeric',
            'max_nilai' => $data['max_nilai'] ?? 100,
            'urutan' => $data['urutan'] ?? $urutan,
        ]);
    }

    public function updateParameter(int $parameterId, array $data): PenilaianParameter
    {
        $parameter = PenilaianParameter::findOrFail($parameterId);
        $minggu = $parameter->minggu()->with('parameters')->first();

        $totalBobot = $minggu->parameters->where('id', '!=', $parameterId)->sum('bobot');
        $bobotBaru = (float) ($data['bobot'] ?? $parameter->bobot);

        if (($totalBobot + $bobotBaru) > 1.00) {
            throw new \Exception('Total bobot melebihi 100%');
        }

        $parameter->update([
            'nama' => $data['nama'] ?? $parameter->nama,
            'bobot' => $data['bobot'] ?? $parameter->bobot,
            'tipe' => $data['tipe'] ?? $parameter->tipe,
            'max_nilai' => $data['max_nilai'] ?? $parameter->max_nilai,
            'urutan' => $data['urutan'] ?? $parameter->urutan,
        ]);

        return $parameter->fresh();
    }

    public function deleteParameter(int $parameterId): void
    {
        $parameter = PenilaianParameter::findOrFail($parameterId);

        if ($parameter->nama === 'presensi') {
            throw new \Exception('Parameter presensi tidak dapat dihapus');
        }

        $parameter->delete();
    }

    public function generatePenilaian(int $mingguId): array
    {
        $minggu = PraktikumMinggu::with(['praktikum.mahasiswa', 'parameters'])->findOrFail($mingguId);
        $praktikum = $minggu->praktikum;
        $mahasiswaList = $praktikum->mahasiswa;

        $strukturMinggu = [];
        foreach ($minggu->parameters as $param) {
            if ($param->tipe === 'presensi') {
                $strukturMinggu[$param->nama] = 'Alfa';
            } else {
                $strukturMinggu[$param->nama] = 0;
            }
        }

        $updated = 0;
        $created = 0;

        DB::transaction(function () use ($mahasiswaList, $minggu, $strukturMinggu, &$updated, &$created) {
            foreach ($mahasiswaList as $mhs) {
                $pivot = $mhs->pivot;
                $nilai = Nilai::firstOrCreate(
                    [
                        'mahasiswa_id' => $mhs->id,
                        'praktikum_id' => $minggu->praktikum_id,
                    ],
                    [
                        'kelompok' => $pivot->kelompok ?? null,
                        'plug' => $pivot->plug ?? null,
                        'nilai_harian' => [],
                    ]
                );

                $nilaiHarian = $nilai->nilai_harian ?? [];

                $indexMinggu = $minggu->minggu_ke - 1;

                if (!isset($nilaiHarian[$indexMinggu])) {
                    $nilaiHarian[$indexMinggu] = $strukturMinggu;
                    $nilai->nilai_harian = $nilaiHarian;
                    $nilai->recalcNilaiAkhir();
                    $nilai->save();
                    $created++;
                } else {
                    foreach ($strukturMinggu as $key => $defaultVal) {
                        if (!array_key_exists($key, $nilaiHarian[$indexMinggu])) {
                            $nilaiHarian[$indexMinggu][$key] = $defaultVal;
                        }
                    }
                    $nilai->nilai_harian = $nilaiHarian;
                    $nilai->recalcNilaiAkhir();
                    $nilai->save();
                    $updated++;
                }
            }
        });

        return [
            'created' => $created,
            'updated' => $updated,
            'total_mahasiswa' => $mahasiswaList->count(),
        ];
    }

    public function previewPenilaian(int $praktikumId): array
    {
        $mingguList = PraktikumMinggu::byPraktikum($praktikumId)
            ->with('parameters')
            ->active()
            ->get();

        $preview = [];

        foreach ($mingguList as $minggu) {
            $kolom = [];
            foreach ($minggu->parameters as $param) {
                $kolom[] = [
                    'nama' => $param->nama,
                    'bobot' => (float) $param->bobot,
                    'tipe' => $param->tipe,
                    'max_nilai' => $param->max_nilai,
                ];
            }

            $totalBobot = array_sum(array_column($kolom, 'bobot'));

            $preview[] = [
                'minggu_ke' => $minggu->minggu_ke,
                'topik' => $minggu->topik,
                'tanggal' => $minggu->tanggal?->toDateString(),
                'kolom_penilaian' => $kolom,
                'total_bobot' => round($totalBobot, 3),
                'is_valid' => abs($totalBobot - 1.00) < 0.001,
            ];
        }

        return $preview;
    }

    public function getDetailPraktikum(int $praktikumId): array
    {
        $praktikum = Praktikum::with(['mahasiswa', 'pengajar'])->findOrFail($praktikumId);

        $mingguList = PraktikumMinggu::byPraktikum($praktikumId)
            ->with('parameters')
            ->get();

        $jadwalList = PraktikumJadwal::byPraktikum($praktikumId)
            ->upcoming()
            ->get();

        return [
            'praktikum' => $praktikum,
            'mahasiswa' => $praktikum->mahasiswa,
            'pengajar' => $praktikum->pengajar,
            'minggu' => $mingguList,
            'jadwal' => $jadwalList,
            'total_mahasiswa' => $praktikum->mahasiswa->count(),
            'total_pengajar' => $praktikum->pengajar->count(),
        ];
    }
}
