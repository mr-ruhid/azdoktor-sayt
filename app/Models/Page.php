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
        'image',            // Səhifənin şəkli / Banner
        'seo_title',        // SEO Başlıq
        'seo_description',  // SEO Açıqlama
        'seo_keywords',     // SEO Açar sözlər
        'is_standard',      // Silinməni əngəlləmək üçün
        'meta'              // Əlavə ayarlar (Həkim sayı və s.)
    ];

    public $translatable = ['title', 'content', 'seo_title', 'seo_description', 'seo_keywords'];

    protected $casts = [
        'status' => 'boolean',
        'is_standard' => 'boolean',
        'meta' => 'array',
    ];

    // Helper: Meta məlumatlarını rahat almaq üçün
    public function getMeta($key, $default = null)
    {
        return $this->meta[$key] ?? $default;
    }
}
