<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'type', // 'post' və ya 'product'
        'status'
    ];

    public $translatable = ['name'];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Blog Məqalələri ilə əlaqə
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Məhsullar ilə əlaqə
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
