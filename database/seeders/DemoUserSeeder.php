<?php

namespace Database\Seeders;

use App\Constant\Users\UserRole;
use App\Entities\Mahasiswa;
use App\Entities\Pengajar;
use App\Entities\Praktikum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $praktikumSutris1 = Praktikum::where('kode', 'SGG001')->first();
        $praktikumHidro = Praktikum::where('kode', 'SGG002')->first();

        $pengajar1 = User::firstOrCreate(
            ['email' => 'pengajar.sutris1@labsgg.local'],
            [
                'name' => 'Pengajar Sutris1',
                'username' => 'pengajar.sutris1',
                'password' => Hash::make('password'),
                'is_verify' => true,
            ]
        );
        $pengajar1->assignRole(UserRole::PENGAJAR);

        Pengajar::firstOrCreate(
            ['user_id' => $pengajar1->id],
            [
                'nama_lengkap' => 'Pengajar Sutris1',
                'praktikum_slug' => $praktikumSutris1?->slug,
                'plug' => [1, 2, 3],
                'is_active' => true,
            ]
        );

        $pengajar2 = User::firstOrCreate(
            ['email' => 'pengajar.hidro@labsgg.local'],
            [
                'name' => 'Pengajar Hidro',
                'username' => 'pengajar.hidro',
                'password' => Hash::make('password'),
                'is_verify' => true,
            ]
        );
        $pengajar2->assignRole(UserRole::PENGAJAR);

        Pengajar::firstOrCreate(
            ['user_id' => $pengajar2->id],
            [
                'nama_lengkap' => 'Pengajar Hidro',
                'praktikum_slug' => $praktikumHidro?->slug,
                'plug' => [4, 5],
                'is_active' => true,
            ]
        );

        $mhs = User::firstOrCreate(
            ['email' => '12345678@labsgg.local'],
            [
                'name' => 'Mahasiswa Demo',
                'username' => '12345678',
                'password' => Hash::make('password'),
                'is_verify' => true,
            ]
        );
        $mhs->assignRole(UserRole::MAHASISWA);

        $mahasiswa = Mahasiswa::firstOrCreate(
            ['nim' => '12345678'],
            [
                'user_id' => $mhs->id,
                'nama_lengkap' => 'Mahasiswa Demo',
                'angkatan' => 2024,
            ]
        );

        if ($praktikumSutris1) {
            $mahasiswa->praktikum()->syncWithoutDetaching([
                $praktikumSutris1->id => ['kelompok' => 1, 'plug' => 1],
            ]);
        }
    }
}
