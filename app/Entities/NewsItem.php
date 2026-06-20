<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'date',
        'category',
        'image',
        'slug',
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

    public function scopeRecent($query, int $limit = 10)
    {
        return $query->orderBy('date', 'desc')->limit($limit);
    }
}
