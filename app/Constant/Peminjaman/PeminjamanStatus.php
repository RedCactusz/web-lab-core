<?php

namespace App\Constant\Peminjaman;

class PeminjamanStatus
{
    const PENDING = 'pending';
    const APPROVED = 'approved';
    const DECLINED = 'declined';
    const COMPLETED = 'completed';
    const MISSED = 'missed';

    public static function all(): array
    {
        return [self::PENDING, self::APPROVED, self::DECLINED, self::COMPLETED, self::MISSED];
    }

    public static function isActive(): array
    {
        return [self::PENDING, self::APPROVED];
    }
}
