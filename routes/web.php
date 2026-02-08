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
use App\Http\Controllers\Admin\MenuController;

// Auth Controllers
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\Auth\UserRegisterController;
use App\Http\Controllers\Auth\DoctorRegisterController;

// Panel Controllers
use App\Http\Controllers\DoctorPanelController;
use App\Http\Controllers\UserPanelController;

// Public Controller
use App\Http\Controllers\PublicController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- AJAX ROUTES (Rezervasiya Sistemi) ---
// Bu routlar lokalizasiya prefix-indən kənarda saxlanılır ki, JS sorğuları xətasız işləsin
Route::get('/api/doctor/slots', [PublicController::class, 'getDoctorSlots'])->name('doctor.slots');
Route::post('/api/doctor/book', [PublicController::class, 'bookAppointment'])->name('doctor.book');


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ],
    function() {

        // --- PUBLIC (FRONTEND) ROUTES ---
        // Bu hissə saytın ön tərəfidir
        Route::get('/', [PublicController::class, 'index'])->name('home');
        Route::get('/about', [PublicController::class, 'about'])->name('about');
        Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
        Route::get('/clinics', [PublicController::class, 'clinics'])->name('clinics');

        // Mağaza & Məhsul Routes
        Route::get('/shop', [PublicController::class, 'shop'])->name('shop');
        Route::get('/shop/{slug}', [PublicController::class, 'productShow'])->name('shop.show');

        // Bloq Routes
        Route::get('/blog', [PublicController::class, 'blog'])->name('blog.index');
        Route::get('/blog/{slug}', [PublicController::class, 'postShow'])->name('blog.show');

        // Xidmətlər Routes
        Route::get('/services', [PublicController::class, 'services'])->name('services');
        Route::get('/service/{slug}', [PublicController::class, 'serviceShow'])->name('service.show');

        // Səbət Əməliyyatları
        Route::get('/cart', [PublicController::class, 'cart'])->name('cart.index');
        Route::post('/cart/add', [PublicController::class, 'addToCart'])->name('cart.add');
        Route::patch('/cart/update', [PublicController::class, 'updateCart'])->name('cart.update');
        Route::delete('/cart/remove', [PublicController::class, 'removeCart'])->name('cart.remove');

        // Həkim Detalları Səhifəsi
        Route::get('/doctor/{id}', [PublicController::class, 'doctorShow'])->name('doctor.show');

        // Şərh Göndərmək
        Route::post('/comment/submit', [PublicController::class, 'submitComment'])->name('comment.submit');

        // Dinamik səhifələr üçün (məs: /privacy-policy)
        Route::get('/page/{slug}', [PublicController::class, 'page'])->name('page.show');


        // --- AUTH ROUTES ---
        // Standart Laravel Auth routlarını söndürürük
        Auth::routes(['register' => false, 'login' => false, 'verify' => true]);

        // Giriş (Login)
        Route::get('login', [UserLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [UserLoginController::class, 'login']);
        Route::post('logout', [UserLoginController::class, 'logout'])->name('logout');

        // Qeydiyyat (Register) - Pasiyentlər üçün
        Route::get('register', [UserRegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [UserRegisterController::class, 'register']);

        // Həkim Qeydiyyatı (Müraciət Formu)
        Route::get('/register/doctor', [DoctorRegisterController::class, 'showRegistrationForm'])->name('register.doctor');
        Route::post('/register/doctor', [DoctorRegisterController::class, 'register'])->name('register.doctor.submit');


        // --- USER & DOCTOR PANELS ---
        Route::group(['middleware' => ['auth']], function () {

            // Həkim Paneli
            Route::get('/doctor-panel', [DoctorPanelController::class, 'index'])->name('doctor.dashboard');
            Route::put('/doctor-panel/reservation/{id}', [DoctorPanelController::class, 'updateStatus'])->name('doctor.reservation.status');

            // Pasiyent Paneli
            Route::get('/my-account', [UserPanelController::class, 'index'])->name('user.dashboard');
            Route::put('/my-account/profile', [UserPanelController::class, 'updateProfile'])->name('user.profile.update');
        });


        // --- ADMIN PANEL ROUTE-LARI ---
        Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {

            // 1. 2FA Səhifələri
            Route::get('2fa', [TwoFactorController::class, 'index'])->name('2fa.index');
            Route::post('2fa', [TwoFactorController::class, 'store'])->name('2fa.store');
            Route::post('2fa/resend', [TwoFactorController::class, 'resend'])->name('2fa.resend');

            // 2. Qorunan Admin Səhifələri
            Route::group(['middleware' => ['admin.2fa']], function () {

                // Başlanğıc
                Route::get('/', function () { return view('admin.dashboard'); })->name('dashboard');

                // Məzmun
                Route::resource('pages', PageController::class);
                Route::resource('menus', MenuController::class);
                Route::post('menus/sort', [MenuController::class, 'sort'])->name('menus.sort');
                Route::get('sidebars', [SidebarController::class, 'index'])->name('sidebars.index');
                Route::get('sidebars/{id}/edit', [SidebarController::class, 'edit'])->name('sidebars.edit');
                Route::put('sidebars/{id}', [SidebarController::class, 'update'])->name('sidebars.update');

                Route::resource('posts', PostController::class);
                Route::resource('categories', CategoryController::class);
                Route::resource('tags', TagController::class);

                // Şərhlər
                Route::prefix('comments')->name('comments.')->group(function() {
                    Route::get('/doctors', [CommentController::class, 'index'])->name('doctors');
                    Route::get('/blogs', [CommentController::class, 'index'])->name('blogs');
                    Route::get('/products', [CommentController::class, 'index'])->name('products');
                    Route::post('/reply', [CommentController::class, 'reply'])->name('reply');
                    Route::put('/{id}/status', [CommentController::class, 'updateStatus'])->name('status');
                    Route::delete('/{id}', [CommentController::class, 'destroy'])->name('destroy');
                });

                // Media & Plugins
                Route::resource('media', MediaController::class)->only(['index', 'store', 'destroy']);
                Route::get('plugins', function() { return 'Plaqinlər'; })->name('plugins.index');

                // Tibb
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

                // E-Ticarət
                Route::resource('services', ServiceController::class);
                Route::resource('products', ProductController::class);
                Route::resource('product_categories', ProductCategoryController::class);
                Route::resource('product_tags', ProductTagController::class);
                Route::resource('coupons', CouponController::class);
                Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
                Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
                Route::put('orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
                Route::delete('orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

                // İstifadəçilər
                Route::resource('users', UserController::class);
                Route::resource('roles', RoleController::class);
                Route::get('subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
                Route::delete('subscribers/{id}', [SubscriberController::class, 'destroy'])->name('subscribers.destroy');
                Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
                Route::get('contacts/{id}', [ContactController::class, 'show'])->name('contacts.show');
                Route::post('contacts/{id}/reply', [ContactController::class, 'reply'])->name('contacts.reply');
                Route::delete('contacts/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');

                // Sistem
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

                // API
                Route::prefix('api')->name('api.')->group(function() {
                    Route::get('my', [ApiController::class, 'index'])->name('my');
                    Route::put('my/{id}', [ApiController::class, 'update'])->name('update');
                    Route::get('shared', [ApiController::class, 'shared'])->name('shared');
                });

                // Sistem Alətləri
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

                // Update & Backup
                Route::prefix('system')->name('system.')->group(function() {
                    Route::get('update', [SystemController::class, 'updateIndex'])->name('update');
                    Route::post('update', [SystemController::class, 'updateStore'])->name('update.store');
                    Route::get('backups', [SystemController::class, 'backupsIndex'])->name('backups');
                    Route::post('backups', [SystemController::class, 'backupStore'])->name('backups.store');
                    Route::get('backups/download/{filename}', [SystemController::class, 'backupDownload'])->name('backups.download');
                    Route::delete('backups/{filename}', [SystemController::class, 'backupDestroy'])->name('backups.destroy');
                    Route::post('backups/restore/{filename}', [SystemController::class, 'backupRestore'])->name('backups.restore');
                });

            }); // End of 2FA

        }); // End of Admin

        // --- Dillər ---
        Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function() {
             Route::resource('languages', LanguageController::class);
             Route::get('languages/{id}/translate', [LanguageController::class, 'translate'])->name('languages.translate');
             Route::post('languages/{id}/translate', [LanguageController::class, 'updateTranslate'])->name('languages.updateTranslate');
        });

    }
);
