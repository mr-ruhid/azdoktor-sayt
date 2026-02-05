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
        // Cache istifadə etmək daha performanslı olar, amma sadəlik üçün birbaşa sorğu edirik
        $isBlocked = BlockedIp::where('ip_address', $request->ip())->exists();

        if ($isBlocked) {
            return abort(403, 'Sizin IP ünvanınız təhlükəsizlik səbəbi ilə bloklanıb.');
        }

        return $next($request);
    }
}
