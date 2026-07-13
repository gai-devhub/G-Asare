<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebContent extends Model
{
    protected $fillable = [
        'hero_image_url',

        'story_title',
        'story_subtitle',
        'story_content',
        'philosophy_title',
        'philosophy_subtitle',
        'philosophy_content',
    ];
}
