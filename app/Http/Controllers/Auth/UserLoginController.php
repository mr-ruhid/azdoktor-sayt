<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Page;
use App\Models\GeneralSetting;
use App\Models\Sidebar;
use App\Models\Menu;

class UserLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | User Login Controller
    |--------------------------------------------------------------------------
    |
    | Bu kontroller istifadəçilərin (Pasiyent və Həkim) tətbiqə girişini idarə edir.
    | Layout məlumatları konstruktorda yüklənir ki, xəta verməsin.
    |
    */

    use AuthenticatesUsers;

    /**
     * Girişdən sonra yönləndirmə (Default)
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Yeni bir controller instansiyası yaradın.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

        // --- LAYOUT ÜÇÜN ORTAQ MƏLUMATLAR ---
        // Bu hissə 'Undefined variable $mobile_navbar' xətasının qarşısını alır
        try {
            $settings = GeneralSetting::first();
            $pc_sidebar = Sidebar::where('type', 'pc_sidebar')->first();
            $mobile_navbar = Sidebar::where('type', 'mobile_navbar')->first();

            $pc_menus = Menu::where('type', 'pc_sidebar')
                ->where('status', true)
                ->whereNull('parent_id')
                ->orderBy('order')
                ->with(['children' => function($q) {
                    $q->where('status', true)->orderBy('order');
                }])
                ->get();

            $mobile_menus = Menu::where('type', 'mobile_navbar')
                ->where('status', true)
                ->whereNull('parent_id')
                ->orderBy('order')
                ->with(['children' => function($q) {
                    $q->where('status', true)->orderBy('order');
                }])
                ->get();

            View::share('settings', $settings);
            View::share('pc_sidebar', $pc_sidebar);
            View::share('mobile_navbar', $mobile_navbar);
            View::share('pc_menus', $pc_menus);
            View::share('mobile_menus', $mobile_menus);

        } catch (\Exception $e) {
            // Miqrasiya və ya baza xətası olarsa səhifənin tam qırılmaması üçün
        }
    }

    /**
     * Giriş formasını göstərin.
     */
    public function showLoginForm()
    {
        $page = new Page();
        $page->setTranslation('title', app()->getLocale(), 'Giriş');

        return view('auth.user_login', compact('page'));
    }

    /**
     * İstifadəçi giriş etdikdən sonra işə düşən metod.
     * Roluna görə yönləndirmə edirik.
     */
    protected function authenticated(Request $request, $user)
    {
        // Əgər Admindirsə (role_type = 1) -> Admin Panelə
        if ($user->role_type == 1) {
            return redirect()->route('admin.dashboard');
        }

        // Əgər Həkimdirsə (role_type = 2) -> Həkim Panelinə
        if ($user->role_type == 2) {
            return redirect()->route('doctor.dashboard');
        }

        // Pasiyent (role_type = 0) -> Ana Səhifəyə
        return redirect()->route('home');
    }
}
