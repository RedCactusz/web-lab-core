<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Dosen::create([
            'nama' => 'Dosen Lab',
            'nip' => 1234567890,
            'surel' => 'dosen@lab.test',
            'pengampu' => 'Pemrograman Web',
            'password' => 'password',
            'is_active' => true,
        ]);
        Dosen::create([
            'nama' => 'Laboran SGG Geomatika',
            'nip' => 111111111,
            'surel' => 'laboran@lab.test',
            'pengampu' => 'Pemrograman Web',
            'password' => 'password',
            'is_active' => true,
        ]);

        Mahasiswa::create([
            'nama' => 'Asisten Aktif',
            'nim' => 2110510001,
            'surel' => 'asisten@lab.test',
            'angkatan' => 2021,
            'is_asisten' => true,
            'pengampu' => 'Pemrograman Web',
            'pengampu_plug' => 'Plug A',
            'is_active_asisten' => true,
            'password' => 'password',
            'is_active_user' => true,
        ]);

        Mahasiswa::create([
            'nama' => 'Mahasiswa Biasa',
            'nim' => 2110510002,
            'surel' => 'mahasiswa@lab.test',
            'angkatan' => 2021,
            'is_asisten' => false,
            'password' => 'password',
            'is_active_user' => true,
        ]);

        Mahasiswa::create([
            'nama' => 'Mahasiswa Nonaktif',
            'nim' => 2110510003,
            'surel' => 'nonaktif@lab.test',
            'angkatan' => 2021,
            'is_asisten' => false,
            'password' => 'password',
            'is_active_user' => false,
        ]);
    }
}
