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
        'short_description',
        'description',
        'price',
        'sale_price',
        'stock_quantity', // DÜZƏLİŞ: 'stock' yerinə 'stock_quantity' (Forma uyğun)
        'sku',
        'image', // Şəkil sütunu
        'status',
        'seo_title',
        'seo_description',
        'seo_keywords'
    ];

    // Tərcümə olunan sahələr
    public $translatable = [
        'name',
        'short_description',
        'description',
        'seo_title',
        'seo_description',
        'seo_keywords'
    ];

    protected $casts = [
        'status' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer', // DÜZƏLİŞ: 'stock' yerinə 'stock_quantity'
    ];

    // Kateqoriya Əlaqəsi
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * View tərəfində getFirstMediaUrl xətasını düzəltmək üçün.
     * 'image' sütunundakı şəkli qaytarır.
     */
    public function getFirstMediaUrl($collectionName = 'default')
    {
        if (!empty($this->image)) {
            // Əgər tam URL-dirsə (http ilə başlayırsa)
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            // Əgər lokal fayldırsa (public qovluğuna əsasən)
            return asset($this->image);
        }

        // Şəkil yoxdursa null qaytarır
        return null;
    }
}
