<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Language;

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
        // Bazadan dilləri oxuyub config-ə yazırıq
        try {
            // Migration işləyən vaxt xəta verməsin deyə yoxlayırıq
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

                    // LaravelLocalization configini dinamik yeniləyirik
                    config(['laravellocalization.supportedLocales' => $supportedLocales]);
                }
            }
        } catch (\Exception $e) {
            // Xəta olsa, sus (ilkin quraşdırma zamanı)
        }
    }
}
