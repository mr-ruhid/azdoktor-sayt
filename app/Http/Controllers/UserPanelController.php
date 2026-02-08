<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Order;
use App\Models\Page;
use App\Models\GeneralSetting;
use App\Models\Sidebar;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;

class UserPanelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // Layout üçün lazımi məlumatlar
        try {
            $settings = GeneralSetting::first();
            $pc_sidebar = Sidebar::where('type', 'pc_sidebar')->first();
            $mobile_navbar = Sidebar::where('type', 'mobile_navbar')->first();
            $pc_menus = Menu::where('type', 'pc_sidebar')->where('status', true)->whereNull('parent_id')->orderBy('order')->with(['children' => function($q) { $q->where('status', true)->orderBy('order'); }])->get();
            $mobile_menus = Menu::where('type', 'mobile_navbar')->where('status', true)->whereNull('parent_id')->orderBy('order')->with(['children' => function($q) { $q->where('status', true)->orderBy('order'); }])->get();

            View::share('settings', $settings);
            View::share('pc_sidebar', $pc_sidebar);
            View::share('mobile_navbar', $mobile_navbar);
            View::share('pc_menus', $pc_menus);
            View::share('mobile_menus', $mobile_menus);
        } catch (\Exception $e) {}
    }

    /**
     * İstifadəçi Paneli (Profil, Rezervasiyalar, Sifarişlər)
     */
    public function index()
    {
        $user = Auth::user();

        // Həkimləri öz panelinə yönləndir
        if ($user->role_type == 2) {
            return redirect()->route('doctor.dashboard');
        }

        // 1. Rezervasiyalarım
        $reservations = Reservation::where('user_id', $user->id)
            ->with('doctor')
            ->orderBy('reservation_date', 'desc')
            ->paginate(10, ['*'], 'reservations_page');

        // 2. Sifarişlərim (Order modeli varsa)
        $orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'orders_page');

        $page = new Page();
        $page->setTranslation('title', app()->getLocale(), 'Hesabım');

        return view('user.dashboard', compact('user', 'reservations', 'orders', 'page'));
    }

    /**
     * Profil Yeniləmə
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'surname', 'email', 'phone', 'birth_date']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profil məlumatlarınız yeniləndi.');
    }
}
