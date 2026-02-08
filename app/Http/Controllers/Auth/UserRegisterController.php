<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View; // View facade
use App\Models\Page;
use App\Models\Specialty;
use App\Models\Clinic;
use App\Models\GeneralSetting; // Layout models
use App\Models\Sidebar;
use App\Models\Menu;

class UserRegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | User Register Controller
    |--------------------------------------------------------------------------
    |
    | Bu kontroller yalnız Frontend (Pasiyent) qeydiyyatı üçündür.
    | Admin panelin qeydiyyatı ilə qarışmaması üçün adı dəyişdirildi.
    |
    */

    use RegistersUsers;

    /**
     * Qeydiyyatdan sonra istifadəçilərin yönləndiriləcəyi yer.
     */
    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');

        // --- LAYOUT ÜÇÜN ORTAQ MƏLUMATLAR ---
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
            // Xəta olarsa susdururuq
        }
    }

    /**
     * Vahid Qeydiyyat Səhifəsini Göstər
     */
    public function showRegistrationForm()
    {
        $page = new Page();
        $page->setTranslation('title', app()->getLocale(), 'Qeydiyyat');

        // Həkim qeydiyyatı tabı üçün lazımi datalar
        $specialties = Specialty::all();
        $clinics = Clinic::where('status', true)->get();

        // Xüsusi 'auth.user_register' görünüşünü çağırırıq
        return view('auth.user_register', compact('page', 'specialties', 'clinics'));
    }

    /**
     * Pasiyent Validasiyası
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['required', 'date'],
        ]);
    }

    /**
     * Pasiyent Hesabı Yarat
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'birth_date' => $data['birth_date'],
            'role_type' => 0, // 0 = Pasiyent (Standart İstifadəçi)
            'password' => Hash::make($data['password']),
        ]);
    }
}
