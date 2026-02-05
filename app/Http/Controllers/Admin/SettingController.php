<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\Language;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    // Tənzimləmələri gətirən köməkçi metod
    private function getSetting()
    {
        $setting = GeneralSetting::first();
        if (!$setting) {
            $setting = GeneralSetting::create(['site_name' => 'AzDoktor']);
        }
        return $setting;
    }

    // Sayt Tənzimləmələri Səhifəsi (Logo, SEO)
    public function site()
    {
        $setting = $this->getSetting();
        $languages = Language::where('status', true)->get();
        return view('admin.settings.site', compact('setting', 'languages'));
    }

    // Sayt Tənzimləmələrini Yenilə
    public function update(Request $request)
    {
        $setting = $this->getSetting();

        $data = $request->only(['site_name', 'seo_title', 'seo_description', 'seo_keywords']);

        // Logo Yükləmə
        if ($request->hasFile('logo')) {
            $this->deleteOldFile($setting->logo);
            $data['logo'] = $this->uploadFile($request->file('logo'));
        }

        // Dark Logo Yükləmə
        if ($request->hasFile('logo_dark')) {
            $this->deleteOldFile($setting->logo_dark);
            $data['logo_dark'] = $this->uploadFile($request->file('logo_dark'));
        }

        // Favicon Yükləmə
        if ($request->hasFile('favicon')) {
            $this->deleteOldFile($setting->favicon);
            $data['favicon'] = $this->uploadFile($request->file('favicon'));
        }

        $setting->update($data);

        return redirect()->back()->with('success', 'Tənzimləmələr uğurla yeniləndi.');
    }

    // --- SMTP (E-poçt) Hissəsi ---

    public function smtp()
    {
        $setting = $this->getSetting();
        return view('admin.settings.smtp', compact('setting'));
    }

    public function smtpUpdate(Request $request)
    {
        $setting = $this->getSetting();

        $data = $request->only([
            'mail_mailer',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name',
        ]);

        $setting->update($data);

        // Config cache-i təmizləyirik ki, yeni ayarlar dərhal tətbiq olunsun
        try {
            Artisan::call('config:clear');
        } catch (\Exception $e) {}

        return redirect()->back()->with('success', 'SMTP ayarları yeniləndi.');
    }

    // Köməkçi funksiyalar: Fayl Yükləmə
    private function uploadFile($file)
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/settings'), $filename);
        return 'uploads/settings/' . $filename;
    }

    // Köməkçi funksiyalar: Fayl Silmə
    private function deleteOldFile($path)
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}
