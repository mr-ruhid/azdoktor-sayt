<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\User;
use App\Mail\TwoFactorMail;
use Carbon\Carbon;

class TwoFactorController extends Controller
{
    // 2FA Səhifəsini göstər
    public function index()
    {
        return view('auth.2fa');
    }

    // Kodu Yoxla
    public function store(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|integer',
        ]);

        $user = Auth::user();
        $ip = $request->ip();
        $key = '2fa_failures:' . $ip; // Bloklama üçün açar

        // 1. Bloklanma Yoxlanışı (3 səhv cəhd)
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return redirect()->back()->withErrors(['two_factor_code' => "Çox sayda uğursuz cəhd. Zəhmət olmasa $seconds saniyə gözləyin."]);
        }

        // 2. Kodun Doğruluğu və Vaxtı
        if ($request->two_factor_code == $user->two_factor_code) {

            if ($user->two_factor_expires_at->lt(now())) {
                RateLimiter::hit($key, 3600); // Səhv cəhd kimi qeydə al
                return redirect()->back()->withErrors(['two_factor_code' => 'Kodun vaxtı bitib. Yenidən göndərin.']);
            }

            // Uğurlu: Kodları silirik, Session yaradırıq, Bloklamanı təmizləyirik
            $user->resetTwoFactorCode();
            session(['2fa_verified' => true]);
            RateLimiter::clear($key);

            return redirect()->route('admin.dashboard');
        }

        // 3. Səhv Kod: Cəhdi artır
        RateLimiter::hit($key, 3600); // 1 saat yadda saxla
        $remaining = RateLimiter::remaining($key, 3);

        return redirect()->back()->withErrors(['two_factor_code' => "Kod yanlışdır. Qalan cəhd haqqınız: $remaining"]);
    }

    // Kodu Yenidən Göndər
    public function resend()
    {
        $user = Auth::user();
        $user->generateTwoFactorCode();

        try {
            Mail::to($user->email)->send(new TwoFactorMail($user->two_factor_code));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'E-poçt göndərilə bilmədi: SMTP xətası.');
        }

        return redirect()->back()->with('success', 'Kod yenidən göndərildi. E-poçtunuzu yoxlayın.');
    }
}
