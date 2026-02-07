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
    // Tənzimləmələri gətirən və ya yoxdursa yaradan köməkçi metod
    private function getSetting()
    {
        $setting = GeneralSetting::first();
        if (!$setting) {
            $setting = GeneralSetting::create(['site_name' => 'AzDoktor']);
        }
        return $setting;
    }

    // --- Sayt Tənzimləmələri (Logo, SEO) ---
    public function site()
    {
        $setting = $this->getSetting();
        $languages = Language::where('status', true)->get();
        return view('admin.settings.site', compact('setting', 'languages'));
    }

    public function update(Request $request)
    {
        $setting = $this->getSetting();

        // SEO və Sayt Adı
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

    // --- Ümumi Ayarlar (Maintenance, Auth, 2FA) və ƏLAQƏ ---
    public function general()
    {
        $setting = $this->getSetting();
        $languages = Language::where('status', true)->get();
        return view('admin.settings.general', compact('setting', 'languages'));
    }

    public function generalUpdate(Request $request)
    {
        $setting = $this->getSetting();

        // 1. Sistem Ayarları (Checkbox-lar)
        $data = [
            'maintenance_mode' => $request->has('maintenance_mode'),
            'enable_registration' => $request->has('enable_registration'),
            'enable_email_verification' => $request->has('enable_email_verification'),
            'enable_social_login' => $request->has('enable_social_login'),
            'auth_2fa_admin' => $request->has('auth_2fa_admin'),
            'auth_2fa_user' => $request->has('auth_2fa_user'),
            'maintenance_text' => $request->input('maintenance_text'), // Tərcüməli
        ];

        // 2. Əlaqə Məlumatları (YENİ ƏLAVƏ)
        $data['phone'] = $request->phone;
        $data['email'] = $request->email;
        $data['map_iframe'] = $request->map_iframe;
        $data['address'] = $request->address; // Tərcüməli sahə (array gəlir)

        // 3. Sosial Media Linkləri (JSON Array)
        $data['social_links'] = [
            'facebook'  => $request->social_facebook,
            'instagram' => $request->social_instagram,
            'twitter'   => $request->social_twitter,
            'whatsapp'  => $request->social_whatsapp,
            'youtube'   => $request->social_youtube,
        ];

        $setting->update($data);

        // Middleware cache təmizləmə
        try {
            Artisan::call('optimize:clear');
        } catch (\Exception $e) {}

        return redirect()->back()->with('success', 'Ümumi və Əlaqə ayarları yeniləndi.');
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
