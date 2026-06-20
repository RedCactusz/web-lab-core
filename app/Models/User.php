<?php

namespace App\Models;

use App\Entities\Alumni;
use App\Entities\Mahasiswa;
use App\Entities\Pengajar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'is_verify',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pengajar(): HasOne
    {
        return $this->hasOne(Pengajar::class);
    }

    public function mahasiswa(): HasOne
    {
        return $this->hasOne(Mahasiswa::class);
    }
}
