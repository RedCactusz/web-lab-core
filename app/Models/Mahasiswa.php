<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable([
    'nama',
    'nim',
    'surel',
    'angkatan',
    'is_asisten',
    'pengampu',
    'pengampu_plug',
    'is_active_asisten',
    'password',
    'is_active_user',
])]
#[Hidden(['password'])]
class Mahasiswa extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'user_mahasiswa';

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_asisten' => 'boolean',
            'is_active_asisten' => 'boolean',
            'is_active_user' => 'boolean',
        ];
    }

    public function getRoleAttribute(): string
    {
        if ($this->is_asisten && $this->is_active_asisten) {
            return 'asisten';
        }

        return 'mahasiswa';
    }
}
