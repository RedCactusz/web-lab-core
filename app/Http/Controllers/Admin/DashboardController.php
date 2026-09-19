<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Praktikum;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    private const HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    public function index(): JsonResponse
    {
        $hariIni = self::HARI[Carbon::now('Asia/Jakarta')->dayOfWeekIso - 1];

        $praktikumHariIni = Praktikum::query()
            ->where('is_active', true)
            ->whereHas('jadwal', fn ($query) => $query
                ->where('hari', $hariIni)
                ->where('is_active', true))
            ->with(['jadwal' => fn ($query) => $query
                ->where('hari', $hariIni)
                ->where('is_active', true)
                ->orderBy('jam_mulai')])
            ->orderBy('praktikum_label')
            ->get(['id', 'praktikum_label', 'praktikum_slug']);

        return response()->json([
            'data' => [
                'mahasiswa_total' => Mahasiswa::count(),
                'praktikum_aktif' => Praktikum::where('is_active', true)->count(),
                'hari_ini' => [
                    'hari' => $hariIni,
                    'jadwal' => $praktikumHariIni->map(fn (Praktikum $praktikum) => [
                        'praktikum' => $praktikum->praktikum_label,
                        'slug' => $praktikum->praktikum_slug,
                        'sesi' => $praktikum->jadwal->map(fn ($jadwal) => [
                            'plug' => $jadwal->plug,
                            'jam_mulai' => $jadwal->jam_mulai?->format('H:i'),
                            'jam_selesai' => $jadwal->jam_selesai?->format('H:i'),
                        ]),
                    ]),
                ],
            ],
        ]);
    }
}
