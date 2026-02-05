<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\Language;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function site()
    {
        // Yalnız bir sətir məlumat olacaq, yoxdursa yaradın
        $setting = GeneralSetting::first();
        if (!$setting) {
            $setting = GeneralSetting::create(['site_name' => 'AzDoktor']);
        }

        // Aktiv dilləri gətiririk ki, tab-larda göstərək
        $languages = Language::where('status', true)->get();

        return view('admin.settings.site', compact('setting', 'languages'));
    }

    public function update(Request $request)
    {
        $setting = GeneralSetting::first();

        // 1. Faylların Yüklənməsi
        $data = $request->only(['site_name', 'seo_title', 'seo_description', 'seo_keywords']);

        // Logo
        if ($request->hasFile('logo')) {
            $this->deleteOldFile($setting->logo);
            $data['logo'] = $this->uploadFile($request->file('logo'));
        }

        // Logo Dark
        if ($request->hasFile('logo_dark')) {
            $this->deleteOldFile($setting->logo_dark);
            $data['logo_dark'] = $this->uploadFile($request->file('logo_dark'));
        }

        // Favicon
        if ($request->hasFile('favicon')) {
            $this->deleteOldFile($setting->favicon);
            $data['favicon'] = $this->uploadFile($request->file('favicon'));
        }

        $setting->update($data);

        return redirect()->back()->with('success', 'Tənzimləmələr uğurla yeniləndi.');
    }

    // Köməkçi funksiyalar
    private function uploadFile($file)
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/settings'), $filename);
        return 'uploads/settings/' . $filename;
    }

    private function deleteOldFile($path)
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}
