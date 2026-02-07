<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title',
        'content',
        'slug',
        'status',
        'image',
        'seo_title',
        'seo_description',
        'seo_keywords'
    ];

    public $translatable = ['title', 'content', 'seo_title', 'seo_description', 'seo_keywords'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
