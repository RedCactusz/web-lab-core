<?php

namespace App\Constant\Users;

class UserRole
{
    const SUPER_ADMIN = 'super-admin';
    const ADMIN = 'admin';
    const PENGAJAR = 'pengajar';
    const MAHASISWA = 'mahasiswa';

    public static function adminRoles(): array
    {
        return [self::SUPER_ADMIN, self::ADMIN];
    }

    public static function all(): array
    {
        return [self::SUPER_ADMIN, self::ADMIN, self::PENGAJAR, self::MAHASISWA];
    }
}
