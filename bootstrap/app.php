<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware Alias-ları (Qısa adlar)
        $middleware->alias([
            // Localization (Dil) Middleware-ləri
            'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            'localizationRedirect' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            'localeViewPath' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
            'localize' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
            'translationRedirect' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,

            // 2FA (İki Faktorlu Təsdiqləmə)
            'admin.2fa' => \App\Http\Middleware\AdminTwoFactor::class,
        ]);

        // Global Middleware (Hər sorğuda işləyənlər)
        // Web qrupuna əlavə edirik ki, bütün veb sorğularında yoxlanılsın
        $middleware->web(append: [
            \App\Http\Middleware\CheckMaintenanceMode::class, // Təmir rejimi yoxlanışı
            \App\Http\Middleware\CheckBlockedIp::class,       // IP Bloklama yoxlanışı
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
