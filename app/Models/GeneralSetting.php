<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class GeneralSetting extends Model
{
    use HasTranslations;

    protected $table = 'general_settings';

    protected $fillable = [
        // Sayt Kimliyi
        'site_name',
        'logo',
        'logo_dark',
        'favicon',

        // SEO Ayarları
        'seo_title',
        'seo_description',
        'seo_keywords',

        // SMTP (E-poçt) Ayarları
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',

        // Ümumi Funksiyalar
        'maintenance_mode',          // Təmir rejimi statusu
        'maintenance_text',          // Təmir rejimi yazısı
        'enable_registration',       // Qeydiyyat
        'enable_email_verification', // E-poçt təsdiqi
        'enable_social_login',       // Sosial giriş master switch
        'auth_2fa_admin',            // Adminlər üçün 2FA
        'auth_2fa_user'              // Userlər üçün 2FA
    ];

    // Tərcümə olunan sahələr (Spatie paketi üçün)
    public $translatable = [
        'seo_title',
        'seo_description',
        'seo_keywords',
        'maintenance_text'
    ];

    // Məlumat tiplərini təyin edirik
    protected $casts = [
        'maintenance_mode' => 'boolean',
        'enable_registration' => 'boolean',
        'enable_email_verification' => 'boolean',
        'enable_social_login' => 'boolean',
        'auth_2fa_admin' => 'boolean',
        'auth_2fa_user' => 'boolean',
    ];
}
