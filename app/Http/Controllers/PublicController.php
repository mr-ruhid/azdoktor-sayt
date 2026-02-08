<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Clinic;
use App\Models\GeneralSetting;
use App\Models\Sidebar;
use App\Models\Product;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\Reservation;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Service;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class PublicController extends Controller
{
    /**
     * Ümumi məlumatları (Layout üçün) bütün view-larda paylaşmaq
     */
    public function __construct()
    {
        try {
            $settings = GeneralSetting::first();
            $pc_sidebar = Sidebar::where('type', 'pc_sidebar')->first();
            $mobile_navbar = Sidebar::where('type', 'mobile_navbar')->first();

            $pc_menus = Menu::where('type', 'pc_sidebar')
                ->where('status', true)
                ->whereNull('parent_id')
                ->orderBy('order')
                ->with(['children' => function($q) {
                    $q->where('status', true)->orderBy('order');
                }])
                ->get();

            $mobile_menus = Menu::where('type', 'mobile_navbar')
                ->where('status', true)
                ->whereNull('parent_id')
                ->orderBy('order')
                ->with(['children' => function($q) {
                    $q->where('status', true)->orderBy('order');
                }])
                ->get();

            View::share('settings', $settings);
            View::share('pc_sidebar', $pc_sidebar);
            View::share('mobile_navbar', $mobile_navbar);
            View::share('pc_menus', $pc_menus);
            View::share('mobile_menus', $mobile_menus);

        } catch (\Exception $e) { }
    }

    // --- SƏHİFƏLƏR ---

    public function index()
    {
        $page = Page::where('slug', 'home')->first();
        if (!$page) {
            $page = new Page();
            $page->setTranslation('title', 'az', 'Ana Səhifə');
            $page->setTranslation('content', 'az', 'Sağlamlığınız əmin əllərdə');
        }

        $specialties = Specialty::all();
        $clinics = Clinic::where('status', true)->get();

        $perPage = $page->getMeta('doctor_count', 12);

        $doctors = Doctor::with(['specialty', 'clinic'])
                         ->where('status', true)
                         ->orderBy('id', 'desc')
                         ->paginate($perPage);

        return view('public.standart.home', compact('page', 'doctors', 'specialties', 'clinics'));
    }

    public function about() {
        $page = Page::where('slug', 'about')->first();
        if (!$page) { $page = new Page(); $page->setTranslation('title', 'az', 'Haqqımızda'); }
        return view('public.standart.about', compact('page'));
    }

    public function contact() {
        $page = Page::where('slug', 'contact')->first();
        if (!$page) { $page = new Page(); $page->setTranslation('title', 'az', 'Əlaqə'); }
        return view('public.standart.contact', compact('page'));
    }

    // --- KLİNİKALAR ---

    public function clinics(Request $request) {
        $page = Page::where('slug', 'clinics')->first();
        if (!$page) { $page = new Page(); $page->setTranslation('title', 'az', 'Klinikalar'); }

        $query = Clinic::where('status', true);

        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('address', 'like', "%{$search}%");
            });
        }

        $clinics = $query->paginate(12);
        $clinics->appends($request->all());

        return view('public.standart.clinics', compact('page', 'clinics'));
    }

    // --- MAĞAZA (SHOP) ---

    public function shop(Request $request) {
        $page = Page::where('slug', 'shop')->first();
        if (!$page) { $page = new Page(); $page->setTranslation('title', 'az', 'Mağaza'); }

        $query = Product::where('status', 1);

        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12);
        $products->appends($request->all());

        $categories = Category::where('type', 'product')->get();

        return view('public.standart.shop', compact('page', 'products', 'categories'));
    }

    public function productShow($slug)
    {
        $product = Product::where('slug', $slug)->where('status', 1)->firstOrFail();
        $page = new Page();
        $page->setTranslation('title', app()->getLocale(), $product->getTranslation('name', app()->getLocale()));
        return view('public.standart.product', compact('product', 'page'));
    }

    // --- BLOQ (NEWS) ---

    public function blog(Request $request)
    {
        $page = Page::where('slug', 'blog')->first();
        if (!$page) {
            $page = new Page();
            $page->setTranslation('title', 'az', 'Bloq');
        }

        $query = Post::where('status', true);

        // Axtarış
        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where('title', 'like', "%{$search}%");
        }

        // Kateqoriya Filteri
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Teq Filteri
        if ($request->has('tag')) {
             $query->whereHas('tags', function($q) use ($request) {
                $q->where('tags.id', $request->tag);
            });
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(9);
        $posts->appends($request->all());

        // Sidebar dataları
        $categories = Category::where('type', 'post')->withCount('posts')->get();
        $recent_posts = Post::where('status', true)->orderBy('created_at', 'desc')->take(3)->get();
        $tags = Tag::all();

        return view('public.standart.blog', compact('page', 'posts', 'categories', 'recent_posts', 'tags'));
    }

    public function postShow($slug)
    {
        $post = Post::where('slug', $slug)->where('status', true)->firstOrFail();

        // Baxış sayını artır
        $post->increment('views');

        $page = new Page();
        $page->setTranslation('title', app()->getLocale(), $post->getTranslation('title', app()->getLocale()));

        return view('public.standart.post', compact('post', 'page'));
    }

    // --- XİDMƏTLƏR ---

    public function services(Request $request)
    {
        $page = Page::where('slug', 'services')->first();
        if (!$page) {
            $page = new Page();
            $page->setTranslation('title', 'az', 'Xidmətlər');
            $page->setTranslation('content', 'az', 'Sizə təklif etdiyimiz tibbi xidmətlər');
        }

        $query = Service::where('status', true);

        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where('name', 'like', "%{$search}%");
        }

        $services = $query->paginate(12);
        $services->appends($request->all());

        return view('public.standart.services', compact('page', 'services'));
    }

    public function serviceShow($slug)
    {
        $service = Service::where('slug', $slug)->where('status', true)->firstOrFail();
        $page = new Page();
        $page->setTranslation('title', app()->getLocale(), $service->getTranslation('name', app()->getLocale()));
        return view('public.standart.service', compact('service', 'page'));
    }

    // --- SƏBƏT (CART) FUNKSİYALARI ---

    public function cart()
    {
        $page = new Page();
        $page->setTranslation('title', app()->getLocale(), 'Səbət');
        return view('public.standart.cart', compact('page'));
    }

    public function addToCart(Request $request)
    {
        $id = $request->id;
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->getTranslation('name', app()->getLocale()),
                "quantity" => 1,
                "price" => $product->sale_price ?: $product->price,
                "image" => $product->getFirstMediaUrl('products') ?: asset('assets/img/no-image.png')
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Məhsul səbətə əlavə edildi!');
    }

    public function updateCart(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Səbət yeniləndi');
        }
    }

    public function removeCart(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Məhsul səbətdən silindi');
        }
    }

    // --- HƏKİM DETALLARI & REZERVASIYA ---

    public function doctorShow($id)
    {
        $doctor = Doctor::with(['clinic', 'specialty', 'comments' => function($q) {
                $q->where('is_approved', true)->orderBy('created_at', 'desc');
            }])
            ->where('status', true)
            ->findOrFail($id);

        $page = new Page();
        $name = $doctor->getTranslation('first_name', app()->getLocale()) . ' ' . $doctor->getTranslation('last_name', app()->getLocale());
        $page->setTranslation('title', app()->getLocale(), $name);

        return view('public.standart.doctor', compact('doctor', 'page'));
    }

    public function submitComment(Request $request)
    {
        $request->validate([
            'commentable_id' => 'required|integer',
            'commentable_type' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required|string|min:5',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $comment = new Comment();
        $comment->commentable_id = $request->commentable_id;
        $comment->commentable_type = $request->commentable_type;
        $comment->name = $request->name;
        $comment->email = $request->email;
        $comment->content = $request->content;
        $comment->rating = $request->rating;
        $comment->is_approved = false;
        $comment->save();

        return redirect()->back()->with('success', 'Rəyiniz qəbul edildi və təsdiq gözləyir.');
    }

    // --- AJAX METODLAR (Rezervasiya) ---

    public function getDoctorSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);
        $slots = $doctor->getAvailableSlots($request->date);

        return response()->json([
            'status' => 'success',
            'slots' => $slots
        ]);
    }

    public function bookAppointment(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $exists = Reservation::where('doctor_id', $request->doctor_id)
            ->where('reservation_date', $request->reservation_date)
            ->where('reservation_time', $request->time)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Təəssüf ki, bu saat artıq tutulub. Zəhmət olmasa başqa vaxt seçin.'
            ], 422);
        }

        $reservation = new Reservation();
        $reservation->doctor_id = $request->doctor_id;
        $reservation->user_id = auth()->id() ?? null;
        $reservation->name = $request->name;
        $reservation->phone = $request->phone;
        $reservation->email = $request->email;
        $reservation->reservation_date = $request->reservation_date;
        $reservation->reservation_time = $request->time;
        $reservation->note = $request->note;
        $reservation->status = 'pending';
        $reservation->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Rezervasiyanız uğurla göndərildi! Tezliklə sizinlə əlaqə saxlanılacaq.'
        ]);
    }

    // Dinamik Səhifə (Ən sonda olmalıdır)
    public function page($slug) {
        $page = Page::where('slug', $slug)->where('status', true)->firstOrFail();
        return view('public.standart.page', compact('page'));
    }
}
