<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Specialty extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'slug', 'icon', 'status'];

    // Tərcümə olunan sütunlar
    public $translatable = ['name'];

    // Həkimlərlə əlaqə (Gələcəkdə lazım olacaq)
    // public function doctors() {
    //     return $this->hasMany(Doctor::class);
    // }
}
