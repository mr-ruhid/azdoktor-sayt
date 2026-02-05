<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'features',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'price',
        'image',
        'icon',
        'status'
    ];

    public $translatable = [
        'name',
        'description',
        'short_description',
        'features',
        'seo_title',
        'seo_description',
        'seo_keywords'
    ];

    protected $casts = [
        'status' => 'boolean',
        'price' => 'decimal:2',
    ];
}
