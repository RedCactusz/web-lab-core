<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'date',
        'time',
        'location',
        'description',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_published' => 'boolean',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc');
    }
}
