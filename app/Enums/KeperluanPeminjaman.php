<?php

namespace App\Enums;

enum KeperluanPeminjaman: string
{
    case Praktikum = 'praktikum';
    case BelajarMandiri = 'belajar_mandiri';
    case Penelitian = 'penelitian';
    case PenyewaanKomersil = 'penyewaan_komersil';

    /**
     * Kode singkat keperluan untuk segment pertama id_log alat_log.
     */
    public function kode(): string
    {
        return match ($this) {
            self::Praktikum => 'prk',
            self::BelajarMandiri => 'bel',
            self::Penelitian => 'pnt',
            self::PenyewaanKomersil => 'swa',
        };
    }

    /**
     * Terjemahan kode keperluan untuk kolom keperluan alat_log.
     */
    public function keterangan(): string
    {
        return match ($this) {
            self::Praktikum => 'praktikum',
            self::BelajarMandiri => 'belajar mandiri',
            self::Penelitian => 'penelitian',
            self::PenyewaanKomersil => 'sewa',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Praktikum => 'Praktikum',
            self::BelajarMandiri => 'Belajar Mandiri',
            self::Penelitian => 'Penelitian',
            self::PenyewaanKomersil => 'Penyewaan Komersil',
        };
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn (self $case) => ['value' => $case->value, 'label' => $case->label()],
            self::cases(),
        );
    }
}
