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
        
        $pathsToCheck = [
            public_path($this->file_path),
            base_path('public_html/' . $this->file_path),
        ];
        
        if (str_starts_with($this->file_path, 'storage/')) {
            $pathsToCheck[] = storage_path('app/public/' . substr($this->file_path, 8));
        }

        foreach ($pathsToCheck as $path) {
            if (file_exists($path) && is_file($path)) {
                $bytes = filesize($path);
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
        }
        
        return '-';
    }
}
