<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Language; // Language modelini əlavə etdik
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class PageController extends Controller
{
    public function index()
    {
        // 1. Bazadakı aktiv dilləri çəkirik
        $activeLanguages = Language::where('status', true)->get();

        // 2. Standart səhifələrin baza tərcümələri (Ərəb dili daxil olmaqla)
        $standardsDefaults = [
            'home' => [
                'az' => 'Ana Səhifə',
                'en' => 'Home',
                'ru' => 'Главная',
                'ar' => 'الرئيسية' // RTL
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

                // Hər bir aktiv dil üçün başlığı təyin edirik
                foreach ($activeLanguages as $lang) {
                    // Əgər massivdə tərcümə varsa götür, yoxdursa EN götür, o da yoxdursa slug-ı yaz
                    $title = $translations[$lang->code] ?? $translations['en'] ?? ucfirst($slug);

                    $page->setTranslation('title', $lang->code, $title);
                    $page->setTranslation('content', $lang->code, ''); // Boş məzmun
                }
                $page->save();
            }
        }

        $pages = Page::orderBy('is_standard', 'desc')->orderBy('created_at', 'desc')->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        // Aktiv dilləri gətiririk
        $languages = Language::where('status', true)->get();
        return view('admin.pages.create', compact('languages'));
    }

    public function store(Request $request)
    {
        // Validasiya: Hazırkı admin panel dilində başlıq mütləq olmalıdır
        $currentLocale = app()->getLocale();

        $request->validate([
            "title.$currentLocale" => 'required',
        ]);

        $page = new Page();
        // Spatie translatable array qəbul edir
        $page->title = $request->title;
        $page->content = $request->content;

        // Slug yaratmaq (Mövcud olan ilk dildən)
        $titleForSlug = $request->title[$currentLocale] ?? array_values($request->title)[0] ?? 'no-title';
        $page->slug = Str::slug($titleForSlug);

        $page->is_standard = false;
        $page->status = $request->has('status');

        // SEO sahələri
        $page->seo_title = $request->seo_title;
        $page->seo_description = $request->seo_description;
        $page->seo_keywords = $request->seo_keywords;

        // Şəkil varsa yüklə
        if ($request->hasFile('image')) {
            $page->image = $this->uploadFile($request->file('image'), 'pages');
        }

        $page->save();

        return redirect()->route('admin.pages.index')->with('success', 'Səhifə yaradıldı.');
    }

    public function edit($id)
    {
        $page = Page::findOrFail($id);
        // Aktiv dilləri gətiririk
        $languages = Language::where('status', true)->get();
        return view('admin.pages.edit', compact('page', 'languages'));
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $page->title = $request->title;
        $page->content = $request->content;

        // Yalnız standart olmayan səhifələrin slug-ı dəyişə bilər
        if (!$page->is_standard) {
            $currentLocale = app()->getLocale();
            $titleForSlug = $request->title[$currentLocale] ?? array_values($request->title)[0] ?? 'no-title';
            $page->slug = Str::slug($titleForSlug);
        }

        $page->status = $request->has('status');

        // SEO sahələri
        $page->seo_title = $request->seo_title;
        $page->seo_description = $request->seo_description;
        $page->seo_keywords = $request->seo_keywords;

        // Meta (Əlavə ayarlar)
        $meta = $page->meta ?? [];

        // Ana Səhifə üçün Xüsusi Ayarlar (Banner, Həkim sayı)
        if ($page->slug == 'home') {
            $meta['doctor_count'] = $request->doctor_count;

            if ($request->hasFile('banner_image')) {
                $meta['banner_image'] = $this->uploadFile($request->file('banner_image'), 'pages/banners');
            }
        }

        // Ümumi şəkil (Banner və ya Paylaşım şəkli)
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
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/' . $folder), $filename);
        return 'uploads/' . $folder . '/' . $filename;
    }
}
