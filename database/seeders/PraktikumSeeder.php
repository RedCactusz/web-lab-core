<?php

namespace Database\Seeders;

use App\Constant\Praktikum\PraktikumSlug;
use App\Entities\Praktikum;
use Illuminate\Database\Seeder;

class PraktikumSeeder extends Seeder
{
    public function run(): void
    {
        $praktikums = [
            [
                'kode' => 'SGG001',
                'nama' => 'Survei Terestris I',
                'slug' => PraktikumSlug::SUTRIS1,
                'deskripsi' => 'Praktikum survei terestris dasar',
            ],
            [
                'kode' => 'SGG002',
                'nama' => 'Survei Hidrografi',
                'slug' => PraktikumSlug::HIDRO,
                'deskripsi' => 'Praktikum survei hidrografi',
            ],
            [
                'kode' => 'SGG003',
                'nama' => 'Survei Rekayasa',
                'slug' => PraktikumSlug::REKAYASA,
                'deskripsi' => 'Praktikum survei rekayasa',
            ],
            [
                'kode' => 'SGG004',
                'nama' => 'Survei Ukur Tambang',
                'slug' => PraktikumSlug::TAMBANG,
                'deskripsi' => 'Praktikum survei ukur tambang',
            ],
            [
                'kode' => 'SGG005',
                'nama' => 'Survei Kadaster',
                'slug' => PraktikumSlug::KADASTER,
                'deskripsi' => 'Praktikum survei kadaster',
            ],
        ];

        foreach ($praktikums as $data) {
            Praktikum::firstOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
