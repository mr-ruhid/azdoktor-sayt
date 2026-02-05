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
        // SMTP Settings (E-poçt ayarları)
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
    ];

    // Tərcümə olunan sahələr (Spatie paketi üçün)
    public $translatable = ['seo_title', 'seo_description', 'seo_keywords'];
}
