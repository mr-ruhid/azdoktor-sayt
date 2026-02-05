<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use Illuminate\Support\Facades\File;

class LanguageController extends Controller
{
    // Bütün dillərin siyahısı
    public function index()
    {
        $languages = Language::all();
        return view('admin.settings.languages.index', compact('languages'));
    }

    // Yeni dil əlavə etmək
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:languages,code',
            'direction' => 'required|in:ltr,rtl',
        ]);

        $code = strtolower($request->code);

        Language::create([
            'name' => $request->name,
            'code' => $code,
            'direction' => $request->direction,
            'is_default' => false,
            'status' => true,
        ]);

        // Yeni dil üçün JSON faylı yarat
        $path = resource_path("lang/{$code}.json");

        // Əgər qovluq yoxdursa yarat (bəzən lang qovluğu olmaya bilər)
        if (!File::exists(resource_path("lang"))) {
            File::makeDirectory(resource_path("lang"));
        }

        if (!File::exists($path)) {
            // Varsayılan dilin sözlərini kopyala (əgər varsa)
            $defaultLang = Language::where('is_default', true)->first();
            $defaultPath = $defaultLang ? resource_path("lang/{$defaultLang->code}.json") : null;

            if ($defaultPath && File::exists($defaultPath)) {
                File::copy($defaultPath, $path);
            } else {
                // Yoxdursa boş JSON yarat
                File::put($path, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
        }

        return redirect()->back()->with('success', 'Yeni dil və tərcümə faylı uğurla yaradıldı.');
    }

    // Dili yeniləmək
    public function update(Request $request, $id)
    {
        $language = Language::findOrFail($id);

        // Əgər "Varsayılan Et" düyməsi basılıbsa
        if ($request->has('set_default')) {
            Language::where('id', '!=', $id)->update(['is_default' => false]);
            $language->update(['is_default' => true, 'status' => true]);

            return redirect()->back()->with('success', 'Varsayılan dil dəyişdirildi.');
        }

        // Əgər sadəcə Status dəyişdirilirsə
        if ($request->has('status')) {
             if ($language->is_default && $request->status == 0) {
                return redirect()->back()->with('error', 'Varsayılan dil deaktiv edilə bilməz.');
             }
             $language->update(['status' => $request->status]);

             return redirect()->back()->with('success', 'Status yeniləndi.');
        }
    }

    // Dili silmək
    public function destroy($id)
    {
        $language = Language::findOrFail($id);

        if ($language->is_default) {
            return redirect()->back()->with('error', 'Varsayılan dil silinə bilməz.');
        }

        // JSON faylını da sil
        $path = resource_path("lang/{$language->code}.json");
        if (File::exists($path)) {
            File::delete($path);
        }

        $language->delete();
        return redirect()->back()->with('success', 'Dil və onun tərcümə faylı silindi.');
    }

    // --- TƏRCÜMƏ METODLARI ---

    // Tərcümə səhifəsini açmaq
    public function translate($id)
    {
        $language = Language::findOrFail($id);

        // JSON faylının yolu
        $path = resource_path("lang/{$language->code}.json");

        // Əgər fayl fiziki olaraq yoxdursa, boşunu yarat
        if (!File::exists($path)) {
             if (!File::exists(resource_path("lang"))) {
                File::makeDirectory(resource_path("lang"));
            }
            File::put($path, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        // Faylı oxu və array-ə çevir
        $jsonContent = File::get($path);
        $translations = json_decode($jsonContent, true);

        // Əgər JSON pozulubsa və ya boşdursa, boş array qaytar
        if (!is_array($translations)) {
            $translations = [];
        }

        return view('admin.settings.languages.translate', compact('language', 'translations'));
    }

    // Tərcümələri yadda saxlamaq
    public function updateTranslate(Request $request, $id)
    {
        $language = Language::findOrFail($id);

        $keys = $request->input('key', []);
        $values = $request->input('value', []);

        $newJson = [];

        // Key və Value arraylarını birləşdirib tək bir array düzəldirik
        foreach($keys as $index => $key) {
            if(!empty($key)) {
                // Key-dəki boşluqları silirik (trim)
                $cleanKey = trim($key);
                $newJson[$cleanKey] = $values[$index] ?? '';
            }
        }

        // JSON formatında fayla yazırıq
        // JSON_PRETTY_PRINT: Oxunaqlı olsun
        // JSON_UNESCAPED_UNICODE: Azərbaycan hərfləri (ə, ö, ü) olduğu kimi qalsın (\u0259 kimi yox)
        $jsonData = json_encode($newJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        File::put(resource_path("lang/{$language->code}.json"), $jsonData);

        return redirect()->back()->with('success', 'Tərcümələr uğurla yeniləndi.');
    }
}
