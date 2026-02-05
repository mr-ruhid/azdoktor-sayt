<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class GeneralSetting extends Model
{
    use HasTranslations;

    protected $table = 'general_settings';

    protected $fillable = [
        'site_name',
        'logo',
        'logo_dark',
        'favicon',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    // Bu sahələrin hər dildə qarşılığı olacaq
    public $translatable = ['seo_title', 'seo_description', 'seo_keywords'];
}
