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

    public $translatable = ['name', 'description', 'short_description'];

    protected $casts = [
        'gallery' => 'array',
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    // Kateqoriya ilə əlaqə
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Teqlər ilə əlaqə
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }
}
