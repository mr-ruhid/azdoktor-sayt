<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Auth;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        // Admin panelinə girişə həmişə icazə ver
        if ($request->is('admin/*') || $request->is('login') || $request->is('logout')) {
            return $next($request);
        }

        try {
            $setting = GeneralSetting::first();

            // Əgər təmir rejimi aktivdirsə
            if ($setting && $setting->maintenance_mode) {

                // Əgər istifadəçi daxil olubsa və Super Admin və ya Administratordursa, burax
                if (Auth::check() && (Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Administrator'))) {
                    return $next($request);
                }

                // Əks halda 503 xətası (Service Unavailable) və mesajı göstər
                $message = $setting->getTranslation('maintenance_text', app()->getLocale()) ?? 'Saytda təmir işləri gedir. Tezliklə qayıdacağıq.';
                return response()->view('errors.503', ['message' => $message], 503);
            }
        } catch (\Exception $e) {
            // Verilənlər bazası xətası olarsa (məsələn, miqrasiya olunmayıbsa) saytı aç
            return $next($request);
        }

        return $next($request);
    }
}
