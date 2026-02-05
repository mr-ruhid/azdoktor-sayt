<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use App\Models\Language;
use App\Models\GeneralSetting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Dinamik Dillər Konfiqurasiyası
        // Bazadakı aktiv dilləri oxuyub LaravelLocalization paketinə ötürürük
        try {
            if (Schema::hasTable('languages')) {
                $languages = Language::where('status', true)->get();
                if ($languages->count() > 0) {
                    $supportedLocales = [];
                    foreach ($languages as $lang) {
                        $supportedLocales[$lang->code] = [
                            'name' => $lang->name,
                            'script' => 'Latn',
                            'native' => $lang->name,
                            'regional' => $lang->code . '_' . strtoupper($lang->code),
                        ];
                    }
                    // Config-i runtime-da dəyişirik
                    config(['laravellocalization.supportedLocales' => $supportedLocales]);
                }
            }
        } catch (\Exception $e) {
            // Migration zamanı xəta verməməsi üçün boş buraxırıq
        }

        // 2. Dinamik SMTP Konfiqurasiyası
        // Bazadakı SMTP ayarlarını oxuyub Laravel Mail sisteminə ötürürük
        try {
            if (Schema::hasTable('general_settings')) {
                $setting = GeneralSetting::first();

                // Əgər bazada SMTP hostu varsa, config-i yeniləyirik
                if ($setting && $setting->mail_host) {
                    $config = [
                        'transport' => $setting->mail_mailer ?? 'smtp',
                        'host' => $setting->mail_host,
                        'port' => $setting->mail_port,
                        'encryption' => $setting->mail_encryption,
                        'username' => $setting->mail_username,
                        'password' => $setting->mail_password,
                        'timeout' => null,
                        'local_domain' => env('MAIL_EHLO_DOMAIN'),
                    ];

                    // Laravel mail konfiqurasiyasını override edirik
                    Config::set('mail.mailers.smtp', $config);

                    if ($setting->mail_from_address) {
                        Config::set('mail.from.address', $setting->mail_from_address);
                    }
                    if ($setting->mail_from_name) {
                        Config::set('mail.from.name', $setting->mail_from_name);
                    }
                }
            }
        } catch (\Exception $e) {
            // Migration zamanı xəta verməməsi üçün boş buraxırıq
        }
    }
}
