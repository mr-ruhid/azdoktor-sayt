<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Clinic;
use App\Models\GeneralSetting;
use App\Models\Sidebar;
use App\Models\Product;          // ƏLAVƏ EDİLDİ
use App\Models\ProductCategory;  // ƏLAVƏ EDİLDİ
use Illuminate\Support\Facades\View;

class PublicController extends Controller
{
    // Ümumi məlumatları (Layout üçün) bütün view-larda paylaşmaq
    public function __construct()
    {
        // Tənzimləmələr yoxdursa xəta verməsin, boş obyekt kimi davransın
        try {
            $settings = GeneralSetting::first();
            $pc_sidebar = Sidebar::where('type', 'pc_sidebar')->first();
            $mobile_navbar = Sidebar::where('type', 'mobile_navbar')->first();

            View::share('settings', $settings);
            View::share('pc_sidebar', $pc_sidebar);
            View::share('mobile_navbar', $mobile_navbar);
        } catch (\Exception $e) {
            // Migrasiya olunmayıbsa xəta verməsin
        }
    }

    // Ana Səhifə
    public function index()
    {
        // "home" slug-ı olan səhifəni bazadan axtarır
        $page = Page::where('slug', 'home')->first();

        // Əgər admin paneldə 'home' səhifəsi yoxdursa, default data göndər
        if (!$page) {
            $page = new Page();
            $page->setTranslation('title', 'az', 'Ana Səhifə');
            $page->setTranslation('content', 'az', 'Xoş gəlmisiniz');
        }

        // Ana səhifədə klinikaları göstərmək üçün
        $clinics = Clinic::where('status', true)->take(6)->get();

        return view('public.standart.home', compact('page', 'clinics'));
    }

    // Haqqımızda
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

    // Əlaqə
    public function contact()
    {
        $page = Page::where('slug', 'contact')->first();

        if (!$page) {
            $page = new Page();
            $page->setTranslation('title', 'az', 'Əlaqə');
        }

        return view('public.standart.contact', compact('page'));
    }

    // Klinikalar
    public function clinics()
    {
        $page = Page::where('slug', 'clinics')->first();

        if (!$page) {
            $page = new Page();
            $page->setTranslation('title', 'az', 'Klinikalar');
        }

        $clinics = Clinic::where('status', true)->paginate(12);

        return view('public.standart.clinics', compact('page', 'clinics'));
    }

    // Mağaza (Shop) - YENİLƏNDİ
    public function shop()
    {
        // 1. Səhifə məlumatları (SEO Title üçün)
        $page = Page::where('slug', 'shop')->first();

        if (!$page) {
            $page = new Page();
            $page->setTranslation('title', 'az', 'Mağaza');
        }

        // 2. Məhsulları çək (Status=1 olanlar, yeni əlavə olunanlar öndə)
        $products = Product::where('status', 1)
                           ->orderBy('created_at', 'desc')
                           ->paginate(12);

        // 3. Kateqoriyaları çək (Sidebar filteri üçün lazım ola bilər)
        $categories = ProductCategory::all();

        // 4. View-a göndər
        return view('public.standart.shop', compact('page', 'products', 'categories'));
    }

    // Dinamik Səhifələr (Məs: /privacy-policy)
    public function page($slug)
    {
        $page = Page::where('slug', $slug)->where('status', true)->firstOrFail();
        return view('public.standart.page', compact('page'));
    }
}
