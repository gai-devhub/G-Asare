<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'icon',
        'title',
        'description',
        'file_path',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    public function getFileSizeAttribute()
    {
        if (empty($this->file_path)) return '-';
        
        $relativePath = $this->file_path;
        if (str_starts_with($relativePath, 'storage/')) {
            $relativePath = substr($relativePath, 8);
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
            $bytes = \Illuminate\Support\Facades\Storage::disk('public')->size($relativePath);
            if ($bytes >= 1048576) {
                return number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                return number_format($bytes / 1024, 0) . ' KB';
            } elseif ($bytes > 1) {
                return $bytes . ' bytes';
            } elseif ($bytes == 1) {
                return $bytes . ' byte';
            } else {
                return '0 bytes';
            }
        }
        
        return '-';
    }
}
