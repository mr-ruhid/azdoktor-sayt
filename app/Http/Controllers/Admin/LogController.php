<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoginLog;
use App\Models\BlockedIp;

class LogController extends Controller
{
    public function index()
    {
        // Son girişləri gətiririk (Səhifələmə ilə)
        $logs = LoginLog::with('user')->latest('login_at')->paginate(20);

        // Bloklanmış IP-ləri gətiririk
        $blockedIps = BlockedIp::latest()->get();

        return view('admin.logs.index', compact('logs', 'blockedIps'));
    }

    public function blockIp(Request $request)
    {
        $request->validate(['ip_address' => 'required|ip']);
        $ip = $request->ip_address;

        // 1. Öz IP-ni bloklamaq olmaz
        if ($ip == $request->ip()) {
            return redirect()->back()->with('error', 'Siz öz IP ünvanınızı bloklaya bilməzsiniz!');
        }

        // 2. Təhlükəsizlik Qaydası: Son 15 gündə bu IP-dən uğurlu giriş olubsa bloklama!
        $isTrusted = LoginLog::where('ip_address', $ip)
                             ->where('login_at', '>=', now()->subDays(15))
                             ->exists();

        if ($isTrusted) {
            return redirect()->back()->with('error', 'Bu IP son 15 gündə istifadə edildiyi üçün "Etibarlı" sayılır və bloklana bilməz.');
        }

        // 3. Blokla
        BlockedIp::firstOrCreate(
            ['ip_address' => $ip],
            ['reason' => 'Admin tərəfindən bloklandı (' . now()->format('d.m.Y H:i') . ')']
        );

        return redirect()->back()->with('success', 'IP ünvanı bloklandı.');
    }

    public function unblockIp($id)
    {
        BlockedIp::destroy($id);
        return redirect()->back()->with('success', 'Blok götürüldü.');
    }
}
