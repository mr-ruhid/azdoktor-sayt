<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorMail;

class AdminTwoFactor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Bazadan tənzimləmələri çəkirik
        // Cache istifadə etmək daha yaxşı olar, amma sadəlik üçün birbaşa çağırırıq
        $setting = GeneralSetting::first();

        // 1. Ayar Aktivdirmi? Və İstifadəçi Admindirmi?
        // auth_2fa_admin sütunu true olmalıdır və istifadəçi Admin rolunda olmalıdır
        if ($setting && $setting->auth_2fa_admin && $user && ($user->hasRole('Super Admin') || $user->hasRole('Administrator'))) {

            // 2. Artıq təsdiqlənibsə (Session-da varsa) burax
            if (session()->has('2fa_verified')) {
                return $next($request);
            }

            // 3. Kod hələ yoxdursa və ya vaxtı bitibsə -> Yarat və Göndər
            // Bu hissə hər səhifə yenilənməsində işləməsin deyə diqqətli olmaq lazımdır.
            // Kod null-dursa və ya vaxtı keçibsə yenisini yaradırıq.
            if (!$user->two_factor_code || ($user->two_factor_expires_at && $user->two_factor_expires_at->lt(now()))) {
                $user->generateTwoFactorCode();
                try {
                    Mail::to($user->email)->send(new TwoFactorMail($user->two_factor_code));
                } catch (\Exception $e) {
                    // SMTP xətası olsa belə davam et, admin view-da görəcək və ya loglara düşəcək
                    // Log::error("2FA Mail Error: " . $e->getMessage());
                }
            }

            // 4. Əgər istifadəçi 2FA səhifəsində deyilsə -> Yönləndir
            // Sonsuz dövrə düşməmək üçün 'admin/2fa' route-nu istisna edirik
            if (!$request->is('admin/2fa*')) {
                return redirect()->route('admin.2fa.index');
            }
        }

        return $next($request);
    }
}
