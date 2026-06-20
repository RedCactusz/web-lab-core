<?php

namespace Database\Seeders;

use App\Constant\Users\UserRole;
use App\Constant\Praktikum\PraktikumSlug;
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
        $admin = User::firstOrCreate(
            ['email' => 'admin@labsgg.local'],
            [
                'name' => 'Admin Lab SGG',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole(UserRole::ADMIN);

        $praktikumSutris1 = Praktikum::where('slug', PraktikumSlug::SUTRIS1)->first();
        $praktikumHidro = Praktikum::where('slug', PraktikumSlug::HIDRO)->first();

        $pengajar1 = User::firstOrCreate(
            ['email' => 'pengajar.sutris1@labsgg.local'],
            [
                'name' => 'Pengajar Sutris1',
                'password' => Hash::make('password'),
            ]
        );
        $pengajar1->assignRole(UserRole::PENGAJAR);

        Pengajar::firstOrCreate(
            ['user_id' => $pengajar1->id],
            [
                'nama_lengkap' => 'Pengajar Sutris1',
                'praktikum_slug' => $praktikumSutris1?->slug,
                'plug' => [1, 2, 3],
            ]
        );

        $pengajar2 = User::firstOrCreate(
            ['email' => 'pengajar.hidro@labsgg.local'],
            [
                'name' => 'Pengajar Hidro',
                'password' => Hash::make('password'),
            ]
        );
        $pengajar2->assignRole(UserRole::PENGAJAR);

        Pengajar::firstOrCreate(
            ['user_id' => $pengajar2->id],
            [
                'nama_lengkap' => 'Pengajar Hidro',
                'praktikum_slug' => $praktikumHidro?->slug,
                'plug' => [4, 5],
            ]
        );

        $mhs = User::firstOrCreate(
            ['email' => '12345678@labsgg.local'],
            [
                'name' => 'Mahasiswa Demo',
                'password' => Hash::make('password'),
            ]
        );
        $mhs->assignRole(UserRole::MAHASISWA);

        Mahasiswa::firstOrCreate(
            ['nim' => '12345678'],
            [
                'user_id' => $mhs->id,
                'nama_lengkap' => 'Mahasiswa Demo',
                'angkatan' => 2024,
            ]
        );
    }
}
