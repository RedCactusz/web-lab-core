<?php

namespace App\Constant\Praktikum;

class PraktikumSlug
{
    const SUTRIS1 = 'sutris1';
    const HIDRO = 'hidro';
    const REKAYASA = 'rekayasa';
    const TAMBANG = 'tambang';
    const KADASTER = 'kadaster';

    public static function all(): array
    {
        return [self::SUTRIS1, self::HIDRO, self::REKAYASA, self::TAMBANG, self::KADASTER];
    }

    public static function labels(): array
    {
        return [
            self::SUTRIS1 => 'Survei Terestris I',
            self::HIDRO => 'Survei Hidrografi',
            self::REKAYASA => 'Survei Rekayasa',
            self::TAMBANG => 'Survei Ukur Tambang',
            self::KADASTER => 'Survei Kadaster',
        ];
    }
}
