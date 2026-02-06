<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\BlockedIp;

class CheckBlockedIp
{
    public function handle(Request $request, Closure $next): Response
    {
        // Bloklanmış IP-ləri yoxla
        // Əgər BlockedIp cədvəli mövcud deyilsə (migrasiya olunmayıbsa) xəta verməsin
        try {
            $isBlocked = BlockedIp::where('ip_address', $request->ip())->exists();

            if ($isBlocked) {
                return abort(403, 'Sizin IP ünvanınız təhlükəsizlik səbəbi ilə bloklanıb.');
            }
        } catch (\Exception $e) {
            // Migrasiya xətası olarsa davam et
        }

        return $next($request);
    }
}
