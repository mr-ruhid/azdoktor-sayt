<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Models\Language;
use App\Models\GeneralSetting;
use App\Models\LoginLog;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 1. Dinamik Dillər Konfiqurasiyası
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
                    config(['laravellocalization.supportedLocales' => $supportedLocales]);
                }
            }
        } catch (\Exception $e) {}

        // 2. Dinamik SMTP Konfiqurasiyası
        try {
            if (Schema::hasTable('general_settings')) {
                $setting = GeneralSetting::first();

                // Əgər bazada SMTP hostu varsa
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

                    // SMTP Ayarlarını tətbiq edirik
                    Config::set('mail.mailers.smtp', $config);

                    // VACİB: Varsayılan mail göndəricisini 'smtp' edirik (log əvəzinə)
                    Config::set('mail.default', 'smtp');

                    if ($setting->mail_from_address) {
                        Config::set('mail.from.address', $setting->mail_from_address);
                    }
                    if ($setting->mail_from_name) {
                        Config::set('mail.from.name', $setting->mail_from_name);
                    }
                }
            }
        } catch (\Exception $e) {}

        // 3. Giriş Logları
        Event::listen(Login::class, function ($event) {
            try {
                if (Schema::hasTable('login_logs')) {
                    LoginLog::create([
                        'user_id' => $event->user->id,
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                        'login_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {}
        });
    }
}
