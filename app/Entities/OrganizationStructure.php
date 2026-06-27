<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class OrganizationStructure extends Model
{
    protected $fillable = [
        'type',
        'name',
        'role',
        'image',
        'section_name',
        'parent_id',
        'order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function parent()
    {
        return $this->belongsTo(OrganizationStructure::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(OrganizationStructure::class, 'parent_id');
    }
}
