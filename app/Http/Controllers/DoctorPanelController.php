<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Page;
use App\Models\GeneralSetting;
use App\Models\Sidebar;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class DoctorPanelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // Layout üçün lazımi məlumatlar
        try {
            $settings = GeneralSetting::first();
            // Sidebar və Menu frontend üçün lazımdırsa boş da olsa göndəririk ki xəta verməsin
            $pc_sidebar = Sidebar::where('type', 'pc_sidebar')->first();
            $mobile_navbar = Sidebar::where('type', 'mobile_navbar')->first();

            View::share('settings', $settings);
            View::share('pc_sidebar', $pc_sidebar);
            View::share('mobile_navbar', $mobile_navbar);
        } catch (\Exception $e) {}
    }

    /**
     * Həkim Paneli (Rezervasiyalar)
     */
    public function index()
    {
        $user = Auth::user();

        // Yalnız Həkimlər daxil ola bilər
        if ($user->role_type != 2 || !$user->doctor) {
            return redirect()->route('home')->with('error', 'Bu səhifəyə giriş icazəniz yoxdur.');
        }

        // Həkimin öz rezervasiyalarını gətiririk
        $reservations = Reservation::where('doctor_id', $user->doctor->id)
            ->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'asc')
            ->paginate(15);

        // Title üçün
        $page = new Page();
        $page->setTranslation('title', app()->getLocale(), 'Həkim Paneli');

        return view('doctor.dashboard', compact('reservations', 'page'));
    }

    /**
     * Statusu Dəyişmək
     */
    public function updateStatus(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        // Təhlükəsizlik: Həkim yalnız öz rezervasiyasını dəyişə bilər
        if (Auth::user()->doctor->id != $reservation->doctor_id) {
            abort(403);
        }

        $reservation->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status yeniləndi.');
    }
}
