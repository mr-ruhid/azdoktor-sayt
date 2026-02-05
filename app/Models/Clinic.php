<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Clinic extends Model
{
    use HasTranslations;

    protected $table = 'clinics';

    protected $fillable = [
        'name',
        'address',
        'description',
        'phone',
        'email',
        'image',
        'status',
        'latitude',
        'longitude'
    ];

    // Spatie paketi bu sütunları avtomatik JSON kimi idarə edəcək
    public $translatable = ['name', 'address', 'description'];
}
