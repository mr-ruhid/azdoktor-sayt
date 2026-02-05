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
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\ApiController;

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

            // Paylaşımlar (Resource)
            Route::resource('posts', PostController::class);

            // Kateqoriyalar (Blog üçün)
            Route::resource('categories', CategoryController::class);

            // Teqlər (Blog üçün)
            Route::resource('tags', TagController::class);

            // Şərhlər (Comments)
            Route::prefix('comments')->name('comments.')->group(function() {
                Route::get('/doctors', [CommentController::class, 'index'])->name('doctors');
                Route::get('/blogs', [CommentController::class, 'index'])->name('blogs');
                Route::get('/products', [CommentController::class, 'index'])->name('products');

                // Ortaq əməliyyatlar
                Route::post('/reply', [CommentController::class, 'reply'])->name('reply');
                Route::put('/{id}/status', [CommentController::class, 'updateStatus'])->name('status');
                Route::delete('/{id}', [CommentController::class, 'destroy'])->name('destroy');
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

            // Xidmətlər (Resource)
            Route::resource('services', ServiceController::class);

            // Məhsullar (Resource)
            Route::resource('products', ProductController::class);

            // Məhsul Kateqoriyaları (Resource)
            Route::resource('product_categories', ProductCategoryController::class);

            // Məhsul Teqləri (Resource)
            Route::resource('product_tags', ProductTagController::class);

            // Kuponlar (Resource)
            Route::resource('coupons', CouponController::class);

            // Sifarişlər (OrderController)
            Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
            Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
            Route::put('orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
            Route::delete('orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

            // --- İstifadəçilər ---
            Route::resource('users', UserController::class);

            // Rollar və İcazələr (Resource Controller)
            Route::resource('roles', RoleController::class);

            // Abunəçilər
            Route::get('subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
            Route::delete('subscribers/{id}', [SubscriberController::class, 'destroy'])->name('subscribers.destroy');

            // Əlaqə Mesajları (ContactController)
            Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
            Route::get('contacts/{id}', [ContactController::class, 'show'])->name('contacts.show');
            Route::post('contacts/{id}/reply', [ContactController::class, 'reply'])->name('contacts.reply');
            Route::delete('contacts/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');

            // --- Sistem & Tənzimləmələr ---

            // Ödəniş Tarixçəsi (PaymentController)
            Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
            Route::delete('payments/{id}', [PaymentController::class, 'destroy'])->name('payments.destroy');

            Route::prefix('settings')->name('settings.')->group(function() {
                Route::get('site', [SettingController::class, 'site'])->name('site');
                Route::put('site', [SettingController::class, 'update'])->name('update');

                // Ümumi Ayarlar (General)
                Route::get('general', [SettingController::class, 'general'])->name('general');
                Route::put('general', [SettingController::class, 'generalUpdate'])->name('general.update');

                // SMTP Ayarları
                Route::get('smtp', [SettingController::class, 'smtp'])->name('smtp');
                Route::put('smtp', [SettingController::class, 'smtpUpdate'])->name('smtp.update');
            });

            // API İnteqrasiyaları
            Route::prefix('api')->name('api.')->group(function() {
                Route::get('my', [ApiController::class, 'index'])->name('my');
                Route::put('my/{id}', [ApiController::class, 'update'])->name('update');
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
