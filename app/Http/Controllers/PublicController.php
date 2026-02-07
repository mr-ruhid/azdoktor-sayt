<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Clinic;
use App\Models\GeneralSetting;
use App\Models\Sidebar;
use App\Models\Product;
use App\Models\Category;
use App\Models\Menu;      // Menyu Builder üçün
use App\Models\Doctor;    // Ana səhifə Həkimlər üçün
use App\Models\Specialty; // Ana səhifə İxtisas filtri üçün
use Illuminate\Support\Facades\View;

class PublicController extends Controller
{
    /**
     * Ümumi məlumatları (Layout üçün) bütün view-larda paylaşmaq
     */
    public function __construct()
    {
        try {
            // Tənzimləmələr və Sidebar Ayarları
            $settings = GeneralSetting::first();
            $pc_sidebar = Sidebar::where('type', 'pc_sidebar')->first();
            $mobile_navbar = Sidebar::where('type', 'mobile_navbar')->first();

            // 1. PC Sidebar Menyuları (Alt menyularla birlikdə)
            $pc_menus = Menu::where('type', 'pc_sidebar')
                ->where('status', true)
                ->whereNull('parent_id') // Yalnız kök menyular
                ->orderBy('order')
                ->with(['children' => function($q) {
                    $q->where('status', true)->orderBy('order');
                }])
                ->get();

            // 2. Mobil Navbar Menyuları
            $mobile_menus = Menu::where('type', 'mobile_navbar')
                ->where('status', true)
                ->whereNull('parent_id')
                ->orderBy('order')
                ->with(['children' => function($q) {
                    $q->where('status', true)->orderBy('order');
                }])
                ->get();

            // Dataları bütün view-larda əlçatan et
            View::share('settings', $settings);
            View::share('pc_sidebar', $pc_sidebar);
            View::share('mobile_navbar', $mobile_navbar);
            View::share('pc_menus', $pc_menus);
            View::share('mobile_menus', $mobile_menus);

        } catch (\Exception $e) {
            // Migrasiya yoxdursa və ya cədvəllər boşdursa xəta verməsin (Fresh install üçün)
        }
    }

    /**
     * Ana Səhifə
     */
    public function index()
    {
        $page = Page::where('slug', 'home')->first();
        if (!$page) {
            $page = new Page();
            $page->setTranslation('title', 'az', 'Ana Səhifə');
            $page->setTranslation('content', 'az', 'Sağlamlığınız əmin əllərdə');
        }

        // --- YENİ ANA SƏHİFƏ MƏNTİQİ ---

        // 1. Axtarış Paneli üçün Filtrlər
        $specialties = Specialty::all(); // İxtisaslar
        $clinics = Clinic::where('status', true)->get(); // Klinikalar

        // 2. Vitrin Həkimləri (Son əlavə olunanlar)
        // Admin paneldə təyin olunan say (Səhifə başına düşən say)
        $perPage = $page->getMeta('doctor_count', 12);

        // 'take()' yerinə 'paginate()' istifadə edirik ki, Blade-də ->links() işləsin
        $doctors = Doctor::with(['specialty', 'clinic'])
                         ->where('status', true)
                         ->orderBy('id', 'desc')
                         ->paginate($perPage);

        return view('public.standart.home', compact('page', 'doctors', 'specialties', 'clinics'));
    }

    /**
     * Haqqımızda
     */
    public function about()
    {
        $page = Page::where('slug', 'about')->first();

        if (!$page) {
            $page = new Page();
            $page->setTranslation('title', 'az', 'Haqqımızda');
            $page->setTranslation('content', 'az', 'Məlumat yoxdur.');
        }

        return view('public.standart.about', compact('page'));
    }

    /**
     * Əlaqə
     */
    public function contact()
    {
        $page = Page::where('slug', 'contact')->first();

        if (!$page) {
            $page = new Page();
            $page->setTranslation('title', 'az', 'Əlaqə');
        }

        return view('public.standart.contact', compact('page'));
    }

    /**
     * Klinikalar Siyahısı
     */
    public function clinics(Request $request)
    {
        $page = Page::where('slug', 'clinics')->first();

        if (!$page) {
            $page = new Page();
            $page->setTranslation('title', 'az', 'Klinikalar');
        }

        $query = Clinic::where('status', true);

        // Axtarış Məntiqi (Ad və Ünvan)
        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $clinics = $query->paginate(12);

        // Axtarış parametrini pagination linklərinə əlavə et
        $clinics->appends($request->all());

        return view('public.standart.clinics', compact('page', 'clinics'));
    }

    /**
     * Mağaza (Shop)
     */
    public function shop(Request $request)
    {
        $page = Page::where('slug', 'shop')->first();

        if (!$page) {
            $page = new Page();
            $page->setTranslation('title', 'az', 'Mağaza');
        }

        // Məhsul sorğusu
        $query = Product::where('status', 1);

        // Axtarış (q parametri varsa)
        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            // JSON sütunda axtarış (bütün dillərdə axtarır)
            $query->where('name', 'like', "%{$search}%");
        }

        // Kateqoriya filteri (opsional)
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Məhsulları gətir (Yenilər öndə)
        $products = $query->orderBy('created_at', 'desc')->paginate(12);

        // Axtarış parametrlərini pagination linklərinə əlavə et
        $products->appends($request->all());

        // Kateqoriyaları gətir (Sidebar filteri üçün)
        $categories = Category::where('type', 'product')->get();

        return view('public.standart.shop', compact('page', 'products', 'categories'));
    }

    /**
     * Dinamik Səhifələr (Məs: /privacy-policy)
     */
    public function page($slug)
    {
        $page = Page::where('slug', $slug)->where('status', true)->firstOrFail();
        return view('public.standart.page', compact('page'));
    }
}
