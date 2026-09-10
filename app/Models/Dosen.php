<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable([
    'nama',
    'nip',
    'surel',
    'pengampu',
    'password',
    'is_active',
])]
#[Hidden(['password'])]
class Dosen extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'user_dosen';

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function getRoleAttribute(): string
    {
        return 'dosen';
    }
}
