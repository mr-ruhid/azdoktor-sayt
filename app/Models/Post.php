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
        'status',
        'is_featured',
        'views', // Baxış sayı
        'seo_title',
        'seo_description',
        'seo_keywords'
    ];

    public $translatable = ['title', 'content', 'seo_title', 'seo_description', 'seo_keywords'];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'views' => 'integer',
    ];

    // Kateqoriya əlaqəsi
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Teqlər əlaqəsi
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // YENİ: Şərhlər əlaqəsi (Xətanı düzəldən hissə)
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
