<?php

namespace Database\Seeders;

use App\Constant\Users\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (UserRole::all() as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
