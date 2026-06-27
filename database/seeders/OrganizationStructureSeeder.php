<?php

namespace Database\Seeders;

use App\Entities\OrganizationStructure;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationStructureSeeder extends Seeder
{
    public function run(): void
    {
        // Kepala Lab
        $kepalaLab = OrganizationStructure::create([
            'type' => 'kepala_lab',
            'name' => 'Nama Kepala Lab',
            'role' => 'Kepala Laboratorium Terpadu',
            'image' => '/placeholder-person.svg',
            'order' => 1,
            'is_published' => true,
        ]);

        // Dosen Lab (6 records)
        $dosenLabData = [];
        for ($i = 1; $i <= 6; $i++) {
            $dosenLabData[] = [
                'type' => 'dosen_lab',
                'name' => "Nama Dosen Lab {$i}",
                'role' => 'Dosen Lab',
                'image' => '/placeholder-person.svg',
                'order' => $i + 1,
                'is_published' => true,
            ];
        }
        foreach ($dosenLabData as $dosen) {
            OrganizationStructure::create($dosen);
        }

        // Praktikum Survei Terestris 1
        $sutris1 = OrganizationStructure::create([
            'type' => 'praktikum_section',
            'section_name' => 'Praktikum Survei Terestris 1',
            'order' => 8,
            'is_published' => true,
        ]);

        $sutris1Pengajar = [
            ['name' => 'M. Rouf Indhra Dewa Sambobo, S.T.', 'image' => '/structure/Sutris1_MRoufIndhraDewaSambobo.JPG', 'order' => 1],
            ['name' => 'Azzahra Nisrina Iskandariah', 'image' => '/structure/Sutris1_AzzahraNisrinaIskandariah.JPG', 'order' => 2],
            ['name' => 'Dewi Tyas Utami', 'image' => '/structure/Sutris1_DewiTyasUtami.JPG', 'order' => 3],
            ['name' => 'M. Luthfi Al Bukhori', 'image' => '/structure/Sutris1_MLuthfiAlBukhori.JPG', 'order' => 4],
            ['name' => 'Rayhan Dwinata Putra', 'image' => '/structure/Sutris1_RayhanDwinataPutra.JPG', 'order' => 5],
        ];
        foreach ($sutris1Pengajar as $pengajar) {
            OrganizationStructure::create([
                'type' => 'praktikum_pengajar',
                'name' => $pengajar['name'],
                'image' => $pengajar['image'],
                'parent_id' => $sutris1->id,
                'order' => $pengajar['order'],
                'is_published' => true,
            ]);
        }

        // Praktikum Survei Hidrografi
        $hidro = OrganizationStructure::create([
            'type' => 'praktikum_section',
            'section_name' => 'Praktikum Survei Hidrografi',
            'order' => 14,
            'is_published' => true,
        ]);

        $hidroPengajar = [
            ['name' => 'Raden Nur Azizah Afiati, S.T.', 'image' => '/structure/Hidro_RadenNurAzizahAfiati.JPG', 'order' => 1],
            ['name' => 'Saud T.P. Pangaribuan, S.T.', 'image' => '/structure/Hidro_SaudTPPangaribuan.JPG', 'order' => 2],
        ];
        foreach ($hidroPengajar as $pengajar) {
            OrganizationStructure::create([
                'type' => 'praktikum_pengajar',
                'name' => $pengajar['name'],
                'image' => $pengajar['image'],
                'parent_id' => $hidro->id,
                'order' => $pengajar['order'],
                'is_published' => true,
            ]);
        }

        // Praktikum Survei Kadaster
        $kadaster = OrganizationStructure::create([
            'type' => 'praktikum_section',
            'section_name' => 'Praktikum Survei Kadaster',
            'order' => 17,
            'is_published' => true,
        ]);

        $kadasterPengajar = [
            ['name' => 'Aldy Lois', 'image' => '/structure/Kadaster_AldyLois.JPG', 'order' => 1],
            ['name' => 'Dimar Fatwa Laksono', 'image' => '/structure/Kadaster_DimarFatwaLaksono.JPG', 'order' => 2],
        ];
        foreach ($kadasterPengajar as $pengajar) {
            OrganizationStructure::create([
                'type' => 'praktikum_pengajar',
                'name' => $pengajar['name'],
                'image' => $pengajar['image'],
                'parent_id' => $kadaster->id,
                'order' => $pengajar['order'],
                'is_published' => true,
            ]);
        }

        // Praktikum Survei Ilmu Ukur Tambang
        $tambang = OrganizationStructure::create([
            'type' => 'praktikum_section',
            'section_name' => 'Praktikum Survei Ilmu Ukur Tambang',
            'order' => 20,
            'is_published' => true,
        ]);

        $tambangPengajar = [
            ['name' => 'Aris Baha Sinaga', 'image' => '/structure/Tambang_ArisBahaSinaga.JPG', 'order' => 1],
            ['name' => 'Devita', 'image' => '/structure/Tambang_Devita.JPG', 'order' => 2],
        ];
        foreach ($tambangPengajar as $pengajar) {
            OrganizationStructure::create([
                'type' => 'praktikum_pengajar',
                'name' => $pengajar['name'],
                'image' => $pengajar['image'],
                'parent_id' => $tambang->id,
                'order' => $pengajar['order'],
                'is_published' => true,
            ]);
        }
    }
}
