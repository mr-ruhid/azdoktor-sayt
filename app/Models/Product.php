<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasTranslations;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        // SEO Fields (YENİ)
        'seo_title',
        'seo_description',
        'seo_keywords',
        // Digər
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'stock_status',
        'image',
        'gallery',
        'status',
        'is_featured'
    ];

    // Bu sahələr tərcümə olunacaq
    public $translatable = [
        'name',
        'description',
        'short_description',
        'seo_title',        // YENİ
        'seo_description',  // YENİ
        'seo_keywords'      // YENİ
    ];

    protected $casts = [
        'gallery' => 'array',
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }
}
