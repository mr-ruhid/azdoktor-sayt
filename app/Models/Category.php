<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'slug', 'type', 'status'];

    public $translatable = ['name'];

    // Paylaşımlarla əlaqə (Gələcəkdə Post modelini yazanda lazım olacaq)
    // public function posts()
    // {
    //     return $this->hasMany(Post::class);
    // }
}
