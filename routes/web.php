<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ClinicController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\DoctorAccountController;
use App\Http\Controllers\Admin\DoctorRequestController; // Yeni Controller

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ],
    function() {

        // Əsas Səhifə (Front)
        Route::get('/', function () {
            return view('welcome');
        });

        // --- ADMIN PANEL ROUTE-LARI ---
        Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

            // Başlanğıc
            Route::get('/', function () { return view('admin.dashboard'); })->name('dashboard');

            // --- Məzmun İdarəetməsi ---
            Route::prefix('pages')->name('pages.')->group(function() {
                Route::get('/', function() { return 'Səhifələr Siyahısı'; })->name('index');
                Route::get('/create', function() { return 'Yeni Səhifə'; })->name('create');
            });

            Route::get('menus', function() { return 'Menyular'; })->name('menus.index');
            Route::get('sidebars', function() { return 'Yan Panellər'; })->name('sidebars.index');

            Route::prefix('posts')->name('posts.')->group(function() {
                Route::get('/', function() { return 'Paylaşımlar'; })->name('index');
                Route::get('/create', function() { return 'Yeni Paylaşım'; })->name('create');
            });

            Route::get('categories', function() { return 'Kateqoriyalar'; })->name('categories.index');
            Route::get('tags', function() { return 'Teqlər'; })->name('tags.index');

            Route::prefix('comments')->name('comments.')->group(function() {
                Route::get('/doctors', function() { return 'Həkim Şərhləri'; })->name('doctors');
                Route::get('/blogs', function() { return 'Bloq Şərhləri'; })->name('blogs');
                Route::get('/products', function() { return 'Məhsul Şərhləri'; })->name('products');
            });

            // Media (Resource)
            Route::resource('media', MediaController::class)->only(['index', 'store', 'destroy']);

            Route::get('plugins', function() { return 'Plaqinlər'; })->name('plugins.index');

            // --- Tibb Bölməsi ---

            // Həkimlər (Resource Controller)
            Route::resource('doctors', DoctorController::class);

            // Həkim Hesabları
            Route::get('doctor-accounts', [DoctorAccountController::class, 'index'])->name('doctor_accounts.index');
            Route::post('doctor-accounts', [DoctorAccountController::class, 'store'])->name('doctor_accounts.store');
            Route::delete('doctor-accounts/{id}', [DoctorAccountController::class, 'destroy'])->name('doctor_accounts.destroy');

            // Klinikalar (Resource)
            Route::resource('clinics', ClinicController::class);

            // İxtisaslar (Specialties) (Resource)
            Route::resource('specialties', SpecialtyController::class);

            Route::get('reservations', function() { return 'Rezervasiyalar'; })->name('reservations.index');

            // YENİ: Həkim İstəkləri (Doctor Requests)
            Route::get('doctor-requests', [DoctorRequestController::class, 'index'])->name('doctor_requests.index');
            Route::put('doctor-requests/{id}/status', [DoctorRequestController::class, 'updateStatus'])->name('doctor_requests.status');
            Route::delete('doctor-requests/{id}', [DoctorRequestController::class, 'destroy'])->name('doctor_requests.destroy');


            // --- E-Ticarət (Aptek) ---

            // Xidmətlər (Satış)
            Route::get('services', function() { return 'Xidmətlər (Satış)'; })->name('services.index');

            Route::get('products', function() { return 'Məhsullar'; })->name('products.index');
            Route::get('product-categories', function() { return 'Məhsul Kateqoriyaları'; })->name('product_categories.index');
            Route::get('product-tags', function() { return 'Məhsul Teqləri'; })->name('product_tags.index');
            Route::get('orders', function() { return 'Sifarişlər'; })->name('orders.index');
            Route::get('coupons', function() { return 'Kuponlar'; })->name('coupons.index');

            // --- İstifadəçilər ---
            Route::get('users', function() { return 'İstifadəçilər'; })->name('users.index');

            // Rollar və İcazələr (Resource Controller)
            Route::resource('roles', RoleController::class);

            Route::get('subscribers', function() { return 'Abunəçilər'; })->name('subscribers.index');
            Route::get('contacts', function() { return 'Əlaqə Mesajları'; })->name('contacts.index');

            // --- Sistem & Tənzimləmələr ---
            Route::get('payments', function() { return 'Ödəniş Tarixçəsi'; })->name('payments.index');

            Route::prefix('settings')->name('settings.')->group(function() {
                Route::get('site', [SettingController::class, 'site'])->name('site');
                Route::put('site', [SettingController::class, 'update'])->name('update');

                Route::get('general', function() { return 'Ümumi Ayarlar'; })->name('general');
                Route::get('smtp', function() { return 'SMTP Ayarları'; })->name('smtp');
            });

            Route::prefix('api')->name('api.')->group(function() {
                Route::get('my', function() { return 'Mənim API-lərim'; })->name('my');
                Route::get('shared', function() { return 'Paylaşılan API-lər'; })->name('shared');
            });

            Route::prefix('tools')->name('tools.')->group(function() {
                Route::get('cache', function() { return 'Cache Təmizləmə'; })->name('cache');
                Route::get('maintenance', function() { return 'Sistem Qulluğu'; })->name('maintenance');
            });

            Route::get('logs', function() { return 'Giriş Logları'; })->name('logs.index');

            Route::prefix('system')->name('system.')->group(function() {
                Route::get('update', function() { return 'Yeniləmə Mərkəzi'; })->name('update');
                Route::get('backups', function() { return 'Ehtiyat Nüsxələr'; })->name('backups');
            });

        });

        // --- Dillər & Tərcümə ---
        Route::group(['prefix' => 'admin'], function() {
             Route::resource('languages', LanguageController::class);

             Route::get('languages/{id}/translate', [LanguageController::class, 'translate'])->name('languages.translate');
             Route::post('languages/{id}/translate', [LanguageController::class, 'updateTranslate'])->name('languages.updateTranslate');
        });

    }
);
