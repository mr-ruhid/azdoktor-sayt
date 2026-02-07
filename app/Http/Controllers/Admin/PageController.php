<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class PageController extends Controller
{
    /**
     * Səhifələrin Siyahısı
     * Əgər standart səhifələr yoxdursa, avtomatik yaradır.
     */
    public function index()
    {
        // 1. Bazadakı aktiv dilləri çəkirik
        $activeLanguages = Language::where('status', true)->get();

        // 2. Standart səhifələrin baza tərcümələri
        $standardsDefaults = [
            'home' => [
                'az' => 'Ana Səhifə',
                'en' => 'Home',
                'ru' => 'Главная',
                'ar' => 'الرئيسية'
            ],
            'about' => [
                'az' => 'Haqqımızda',
                'en' => 'About Us',
                'ru' => 'О нас',
                'ar' => 'من نحن'
            ],
            'contact' => [
                'az' => 'Əlaqə',
                'en' => 'Contact',
                'ru' => 'Контакты',
                'ar' => 'اتصل بنا'
            ],
            'clinics' => [
                'az' => 'Klinikalar',
                'en' => 'Clinics',
                'ru' => 'Клиники',
                'ar' => 'العيادات'
            ],
            'shop' => [
                'az' => 'Mağaza',
                'en' => 'Shop',
                'ru' => 'Магазин',
                'ar' => 'المتجر'
            ],
        ];

        // 3. Yoxlayırıq: əgər standart səhifələr yoxdursa, yaradırıq
        foreach ($standardsDefaults as $slug => $translations) {
            if (!Page::where('slug', $slug)->exists()) {
                $page = new Page();
                $page->slug = $slug;
                $page->is_standard = true;
                $page->status = true;

                foreach ($activeLanguages as $lang) {
                    $title = $translations[$lang->code] ?? $translations['en'] ?? ucfirst($slug);
                    $page->setTranslation('title', $lang->code, $title);
                    $page->setTranslation('content', $lang->code, '');
                }
                $page->save();
            }
        }

        $pages = Page::orderBy('is_standard', 'desc')->orderBy('created_at', 'desc')->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        $languages = Language::where('status', true)->get();
        return view('admin.pages.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $currentLocale = app()->getLocale();

        $request->validate([
            "title.$currentLocale" => 'required',
        ]);

        $page = new Page();
        $page->title = $request->title;
        $page->content = $request->content;

        $titleForSlug = $request->title[$currentLocale] ?? array_values($request->title)[0] ?? 'no-title';
        $page->slug = Str::slug($titleForSlug);

        $page->is_standard = false;
        $page->status = $request->has('status');

        $page->seo_title = $request->seo_title;
        $page->seo_description = $request->seo_description;
        $page->seo_keywords = $request->seo_keywords;

        if ($request->hasFile('image')) {
            $page->image = $this->uploadFile($request->file('image'), 'pages');
        }

        $page->save();

        return redirect()->route('admin.pages.index')->with('success', 'Səhifə yaradıldı.');
    }

    public function edit($id)
    {
        $page = Page::findOrFail($id);
        $languages = Language::where('status', true)->get();
        return view('admin.pages.edit', compact('page', 'languages'));
    }

    /**
     * YENİLƏMƏ METODU (Ən Vacib Hissə)
     * Burada "Haqqımızda" səhifəsinin blokları işlənir.
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        // 1. Standart Məlumatlar
        $page->title = $request->title;
        $page->content = $request->content;

        // Standart səhifələrin URL-i dəyişməsin
        if (!$page->is_standard) {
            $currentLocale = app()->getLocale();
            $titleForSlug = $request->title[$currentLocale] ?? array_values($request->title)[0] ?? 'no-title';
            $page->slug = Str::slug($titleForSlug);
        }

        $page->status = $request->has('status');

        // SEO
        $page->seo_title = $request->seo_title;
        $page->seo_description = $request->seo_description;
        $page->seo_keywords = $request->seo_keywords;

        // --- META MƏLUMATLARI (JSON) ---
        $meta = $page->meta ?? [];

        // A) ANA SƏHİFƏ AYARLARI
        if ($page->slug == 'home') {
            $meta['doctor_count'] = $request->doctor_count;

            if ($request->hasFile('banner_image')) {
                $meta['banner_image'] = $this->uploadFile($request->file('banner_image'), 'pages/banners');
            }
        }

        // B) HAQQIMIZDA SƏHİFƏSİ - DİNAMİK BLOKLAR
        if ($page->slug == 'about') {
            $sections = [];

            // Yalnız formda sections varsa emal edirik
            if ($request->has('sections')) {
                foreach ($request->sections as $key => $sectionData) {
                    // Mövcud data strukturunu qururuq
                    $section = [
                        'title' => $sectionData['title'] ?? [],
                        'content' => $sectionData['content'] ?? [],
                        'image' => $sectionData['old_image'] ?? null, // Əgər yeni şəkil yoxdursa, köhnəni saxla
                    ];

                    // Yeni şəkil yüklənibsə, onu əvəz et
                    if ($request->hasFile("sections.$key.image")) {
                        $section['image'] = $this->uploadFile($request->file("sections.$key.image"), 'pages/about');
                    }

                    $sections[] = $section;
                }
            }

            // Hazır massivi meta-ya yazırıq
            $meta['sections'] = $sections;
        }

        // Ümumi Səhifə Şəkli (SEO Image və ya Banner)
        if ($request->hasFile('image')) {
            $page->image = $this->uploadFile($request->file('image'), 'pages');
        }

        $page->meta = $meta;
        $page->save();

        return redirect()->route('admin.pages.index')->with('success', 'Səhifə yeniləndi.');
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);

        if ($page->is_standard) {
            return redirect()->back()->with('error', 'Bu standart sistem səhifəsidir, silinə bilməz!');
        }

        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Səhifə silindi.');
    }

    // Şəkil yükləmək üçün köməkçi funksiya
    private function uploadFile($file, $folder)
    {
        // Unikal ad yaradırıq ki, çakışma olmasın
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/' . $folder), $filename);
        return 'uploads/' . $folder . '/' . $filename;
    }
}
