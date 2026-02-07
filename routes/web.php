<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// Admin Controllers
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
use App\Http\Controllers\Admin\TwoFactorController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\SidebarController;
use App\Http\Controllers\Admin\PageController;

// Public Controller
use App\Http\Controllers\PublicController;

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

        // --- PUBLIC (FRONTEND) ROUTES ---
        // Bu hissə saytın ön tərəfidir (Admin olmayanlar üçün)

        Route::get('/', [PublicController::class, 'index'])->name('home');
        Route::get('/about', [PublicController::class, 'about'])->name('about');
        Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
        Route::get('/clinics', [PublicController::class, 'clinics'])->name('clinics');
        Route::get('/shop', [PublicController::class, 'shop'])->name('shop');
        // Dinamik səhifələr üçün (məs: /privacy-policy)
        Route::get('/page/{slug}', [PublicController::class, 'page'])->name('page.show');


        // --- AUTH ROUTES ---
        Auth::routes(['register' => false, 'verify' => true]);


        // --- ADMIN PANEL ROUTE-LARI ---
        Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {

            // 1. 2FA Səhifələri (Middleware-dən KƏNAR)
            Route::get('2fa', [TwoFactorController::class, 'index'])->name('2fa.index');
            Route::post('2fa', [TwoFactorController::class, 'store'])->name('2fa.store');
            Route::post('2fa/resend', [TwoFactorController::class, 'resend'])->name('2fa.resend');

            // 2. Qorunan Admin Səhifələri (2FA Middleware tətbiq olunur)
            Route::group(['middleware' => ['admin.2fa']], function () {

                // Başlanğıc
                Route::get('/', function () { return view('admin.dashboard'); })->name('dashboard');

                // --- Məzmun İdarəetməsi ---

                // Səhifələr
                Route::resource('pages', PageController::class);

                Route::prefix('pages')->name('pages.')->group(function() {
                    Route::get('/', function() { return 'Səhifələr Siyahısı'; })->name('index');
                    Route::get('/create', function() { return 'Yeni Səhifə'; })->name('create');
                });

                Route::get('menus', function() { return 'Menyular'; })->name('menus.index');

                // Yan Panellər (Sidebars)
                Route::get('sidebars', [SidebarController::class, 'index'])->name('sidebars.index');
                Route::get('sidebars/{id}/edit', [SidebarController::class, 'edit'])->name('sidebars.edit');
                Route::put('sidebars/{id}', [SidebarController::class, 'update'])->name('sidebars.update');

                // Paylaşımlar
                Route::resource('posts', PostController::class);
                Route::resource('categories', CategoryController::class);
                Route::resource('tags', TagController::class);

                // Şərhlər
                Route::prefix('comments')->name('comments.')->group(function() {
                    Route::get('/doctors', [CommentController::class, 'index'])->name('doctors');
                    Route::get('/blogs', [CommentController::class, 'index'])->name('blogs');
                    Route::get('/products', [CommentController::class, 'index'])->name('products');

                    // Ortaq əməliyyatlar
                    Route::post('/reply', [CommentController::class, 'reply'])->name('reply');
                    Route::put('/{id}/status', [CommentController::class, 'updateStatus'])->name('status');
                    Route::delete('/{id}', [CommentController::class, 'destroy'])->name('destroy');
                });

                // Media
                Route::resource('media', MediaController::class)->only(['index', 'store', 'destroy']);

                Route::get('plugins', function() { return 'Plaqinlər'; })->name('plugins.index');

                // --- Tibb Bölməsi ---
                Route::resource('doctors', DoctorController::class);
                Route::get('doctor-accounts', [DoctorAccountController::class, 'index'])->name('doctor_accounts.index');
                Route::post('doctor-accounts', [DoctorAccountController::class, 'store'])->name('doctor_accounts.store');
                Route::delete('doctor-accounts/{id}', [DoctorAccountController::class, 'destroy'])->name('doctor_accounts.destroy');
                Route::resource('clinics', ClinicController::class);
                Route::resource('specialties', SpecialtyController::class);
                Route::get('reservations', [ReservationController::class, 'index'])->name('reservations.index');
                Route::put('reservations/{id}/status', [ReservationController::class, 'updateStatus'])->name('reservations.status');
                Route::delete('reservations/{id}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
                Route::get('doctor-requests', [DoctorRequestController::class, 'index'])->name('doctor_requests.index');
                Route::put('doctor-requests/{id}/status', [DoctorRequestController::class, 'updateStatus'])->name('doctor_requests.status');
                Route::delete('doctor-requests/{id}', [DoctorRequestController::class, 'destroy'])->name('doctor_requests.destroy');

                // --- E-Ticarət ---
                Route::resource('services', ServiceController::class);
                Route::resource('products', ProductController::class);
                Route::resource('product_categories', ProductCategoryController::class);
                Route::resource('product_tags', ProductTagController::class);
                Route::resource('coupons', CouponController::class);
                Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
                Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
                Route::put('orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
                Route::delete('orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

                // --- İstifadəçilər ---
                Route::resource('users', UserController::class);
                Route::resource('roles', RoleController::class);
                Route::get('subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
                Route::delete('subscribers/{id}', [SubscriberController::class, 'destroy'])->name('subscribers.destroy');
                Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
                Route::get('contacts/{id}', [ContactController::class, 'show'])->name('contacts.show');
                Route::post('contacts/{id}/reply', [ContactController::class, 'reply'])->name('contacts.reply');
                Route::delete('contacts/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');

                // --- Sistem ---
                Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
                Route::delete('payments/{id}', [PaymentController::class, 'destroy'])->name('payments.destroy');

                Route::prefix('settings')->name('settings.')->group(function() {
                    Route::get('site', [SettingController::class, 'site'])->name('site');
                    Route::put('site', [SettingController::class, 'update'])->name('update');

                    Route::get('general', [SettingController::class, 'general'])->name('general');
                    Route::put('general', [SettingController::class, 'generalUpdate'])->name('general.update');

                    Route::get('smtp', [SettingController::class, 'smtp'])->name('smtp');
                    Route::put('smtp', [SettingController::class, 'smtpUpdate'])->name('smtp.update');
                });

                // API İnteqrasiyaları
                Route::prefix('api')->name('api.')->group(function() {
                    Route::get('my', [ApiController::class, 'index'])->name('my');
                    Route::put('my/{id}', [ApiController::class, 'update'])->name('update');
                    Route::get('shared', [ApiController::class, 'shared'])->name('shared');
                });

                // Sistem Alətləri (Keş və Baxım)
                Route::prefix('tools')->name('tools.')->group(function() {
                    Route::get('cache', [ToolController::class, 'cacheIndex'])->name('cache');
                    Route::get('cache/clear/{type}', [ToolController::class, 'cacheClear'])->name('cache.clear');
                    Route::get('maintenance', [ToolController::class, 'maintenance'])->name('maintenance');
                    Route::post('maintenance/{action}', [ToolController::class, 'maintenanceAction'])->name('maintenance.action');
                });

                // Loglar
                Route::get('logs', [LogController::class, 'index'])->name('logs.index');
                Route::post('logs/block', [LogController::class, 'blockIp'])->name('logs.block');
                Route::delete('logs/unblock/{id}', [LogController::class, 'unblockIp'])->name('logs.unblock');

                // Update & Backup (SystemController)
                Route::prefix('system')->name('system.')->group(function() {
                    // Update
                    Route::get('update', [SystemController::class, 'updateIndex'])->name('update');
                    Route::post('update', [SystemController::class, 'updateStore'])->name('update.store');

                    // Backup
                    Route::get('backups', [SystemController::class, 'backupsIndex'])->name('backups');
                    Route::post('backups', [SystemController::class, 'backupStore'])->name('backups.store');
                    Route::get('backups/download/{filename}', [SystemController::class, 'backupDownload'])->name('backups.download');
                    Route::delete('backups/{filename}', [SystemController::class, 'backupDestroy'])->name('backups.destroy');
                    Route::post('backups/restore/{filename}', [SystemController::class, 'backupRestore'])->name('backups.restore');
                });

            }); // End of 2FA Middleware Group

        }); // End of Admin Group

        // --- Dillər & Tərcümə ---
        Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function() {
             Route::resource('languages', LanguageController::class);
             Route::get('languages/{id}/translate', [LanguageController::class, 'translate'])->name('languages.translate');
             Route::post('languages/{id}/translate', [LanguageController::class, 'updateTranslate'])->name('languages.updateTranslate');
        });

    }
);
