<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use HasTranslations;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'content',
        'image',
        'view_count',
        'status',
        'is_featured',
        // SEO Fields
        'seo_title',
        'seo_description',
        'seo_keywords'
    ];

    // Bu sahələr hər dildə fərqli olacaq
    public $translatable = ['title', 'content', 'seo_title', 'seo_description', 'seo_keywords'];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }
}
