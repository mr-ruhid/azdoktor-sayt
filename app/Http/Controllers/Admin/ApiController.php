<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiCredential;
use Illuminate\Support\Facades\Artisan;

class ApiController extends Controller
{
    // API Ayarları Listesi
    public function index()
    {
        // Kateqoriyalara görə qruplaşdırırıq
        $apis = ApiCredential::all()->groupBy('category');
        return view('admin.api.my', compact('apis'));
    }

    // API Ayarlarını Güncelleme
    public function update(Request $request, $id)
    {
        $api = ApiCredential::findOrFail($id);

        // Dinamik sahələri yenilə
        $credentials = $api->credentials;

        if ($request->has('credentials')) {
            foreach ($request->credentials as $key => $value) {
                // Yalnız mövcud açarları yenilə (təhlükəsizlik üçün)
                if (array_key_exists($key, $credentials)) {
                    $credentials[$key] = $value;
                }
            }
        }

        $api->credentials = $credentials;
        $api->status = $request->has('status');
        $api->save();

        // Config cache-i təmizləmək yaxşı olar (əgər config-dən oxunursa)
        try {
            Artisan::call('config:clear');
        } catch (\Exception $e) {}

        return redirect()->back()->with('success', $api->name . ' tənzimləmələri yeniləndi.');
    }

    // --- Paylaşılan API-lər Səhifəsi (Documentation) ---
    public function shared()
    {
        return view('admin.api.shared');
    }
}
