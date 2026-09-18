<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
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
    }
}
