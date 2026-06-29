<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kerjasama extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kerjasama';

    protected $fillable = [
        'title',
        'description',
        'partner_name',
        'logo',
        'start_date',
        'end_date',
        'status',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_published' => 'boolean',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
