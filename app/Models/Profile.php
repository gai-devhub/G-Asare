<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'name',
        'tagline',
        'badge_text',
        'bio',
        'image_url',
        'email',
        'phone',
        'location',
        'education_name',
        'stat_projects',
        'stat_clients',
        'stat_years',
        'stat_technologies',
        'typing_phrases',
        'social_links',
        'contact_info',
    ];

    protected function casts(): array
    {
        return [
            'typing_phrases' => 'array',
            'social_links' => 'array',
            'contact_info' => 'array',
        ];
    }
}
