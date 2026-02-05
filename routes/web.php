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
use App\Http\Controllers\Admin\DoctorRequestController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductTagController;

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

        // --- ADMIN PANEL ROUTE-LARI (Prefix: admin, Name Prefix: admin.) ---
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

            // Paylaşımlar (Resource)
            Route::resource('posts', PostController::class);

            // Kateqoriyalar (Blog üçün)
            Route::resource('categories', CategoryController::class);

            // Teqlər (Blog üçün)
            Route::resource('tags', TagController::class);

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

            // Rezervasiyalar (Controller)
            Route::get('reservations', [ReservationController::class, 'index'])->name('reservations.index');
            Route::put('reservations/{id}/status', [ReservationController::class, 'updateStatus'])->name('reservations.status');
            Route::delete('reservations/{id}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

            // Həkim İstəkləri (Doctor Requests)
            Route::get('doctor-requests', [DoctorRequestController::class, 'index'])->name('doctor_requests.index');
            Route::put('doctor-requests/{id}/status', [DoctorRequestController::class, 'updateStatus'])->name('doctor_requests.status');
            Route::delete('doctor-requests/{id}', [DoctorRequestController::class, 'destroy'])->name('doctor_requests.destroy');


            // --- E-Ticarət (Aptek) ---

            // Xidmətlər (Satış) - Placeholder
            Route::get('services', function() { return 'Xidmətlər (Satış)'; })->name('services.index');

            // Məhsullar (Resource)
            Route::resource('products', ProductController::class);

            // Məhsul Kateqoriyaları (Resource)
            Route::resource('product-categories', ProductCategoryController::class);

            // Məhsul Teqləri (Resource)
            Route::resource('product-tags', ProductTagController::class);

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

        // --- Dillər & Tərcümə (Resource default naming işlədir) ---
        Route::group(['prefix' => 'admin'], function() {
             Route::resource('languages', LanguageController::class);

             Route::get('languages/{id}/translate', [LanguageController::class, 'translate'])->name('languages.translate');
             Route::post('languages/{id}/translate', [LanguageController::class, 'updateTranslate'])->name('languages.updateTranslate');
        });

    }
);
