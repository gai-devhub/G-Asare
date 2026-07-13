<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = [
        'category',
        'sub_category',
        'description',
        'name',
        'percentage',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'percentage' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('category')->orderBy('sub_category')->orderBy('sort_order')->orderBy('name');
    }
}
