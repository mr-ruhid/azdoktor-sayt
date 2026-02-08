@extends('layouts.public')

@section('title', $page->getTranslation('title', app()->getLocale()))

@section('content')
<div class="container py-5">

    {{-- Başlıq, Axtarış və Kateqoriyalar --}}
    <div class="row mb-5 gy-3 align-items-center">

        {{-- Sol: Başlıq və Say --}}
        <div class="col-lg-4 col-md-6">
            <h1 class="fw-bold mb-0">{{ $page->getTranslation('title', app()->getLocale()) }}</h1>
            <p class="text-muted mt-1 mb-0">{{ $products->total() }} {{ __('shop.products_available', ['default' => 'məhsul mövcuddur']) }}</p>
        </div>

        {{-- Orta: Axtarış Paneli --}}
        <div class="col-lg-4 col-md-6">
             <form action="{{ route('shop') }}" method="GET">
                <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white border">
                    <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control border-0 shadow-none" placeholder="{{ __('shop.search_placeholder', ['default' => 'Məhsul adı axtar...']) }}" value="{{ request('q') }}">
                    <button class="btn btn-primary px-4 border-0" type="submit">{{ __('shop.search_btn', ['default' => 'Axtar']) }}</button>
                </div>
            </form>
        </div>

        {{-- Sağ: Kateqoriya Filtrləri --}}
        <div class="col-lg-4 col-12 text-lg-end">
            <div class="d-inline-block">
                {{-- "Hamısı" düyməsi --}}
                <a href="{{ route('shop') }}" class="btn btn-sm {{ !request('category') ? 'btn-dark' : 'btn-outline-dark' }} rounded-pill px-3 m-1">{{ __('shop.all_categories', ['default' => 'Hamısı']) }}</a>

                {{-- Kateqoriya düymələri --}}
                @foreach($categories as $category)
                    <a href="{{ route('shop', ['category' => $category->id]) }}" class="btn btn-sm {{ request('category') == $category->id ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-3 m-1">
                        {{ $category->getTranslation('name', app()->getLocale()) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Məhsullar Grid --}}
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm border-0 product-card">

                    {{-- Endirim Etiketi --}}
                    @if($product->sale_price)
                        <div class="position-absolute top-0 start-0 p-3 z-1">
                            <span class="badge bg-danger rounded-pill">-{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%</span>
                        </div>
                    @endif

                    {{-- Şəkil Hissəsi --}}
                    <div class="position-relative overflow-hidden bg-light rounded-top" style="height: 220px;">
                        {{-- Məhsul şəkli yoxdursa placeholder göstəririk --}}
                        <img src="{{ $product->getFirstMediaUrl('products') ?: asset('assets/img/no-image.png') }}"
                             class="card-img-top w-100 h-100 object-fit-cover"
                             alt="{{ $product->getTranslation('name', app()->getLocale()) }}">

                        {{-- Hover Actions (Üzərinə gələndə çıxan düymələr) --}}
                        <div class="product-actions position-absolute start-0 w-100 h-100 d-flex justify-content-center align-items-center"
                             style="background: rgba(0,0,0,0.4); top: 100%; transition: top 0.3s;">

                            {{-- Ətraflı düyməsi (Link əlavə edildi) --}}
                            <a href="{{ route('shop.show', $product->slug) }}" class="btn btn-light rounded-circle mx-2" title="{{ __('shop.view_details', ['default' => 'Ətraflı Bax']) }}">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- Səbət düyməsi (Form əlavə edildi) --}}
                            <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-primary rounded-circle mx-2" title="{{ __('shop.add_to_cart', ['default' => 'Səbətə At']) }}">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Məlumat Hissəsi --}}
                    <div class="card-body d-flex flex-column">
                        {{-- Kateqoriya Adı --}}
                        @if($product->category)
                            <small class="text-muted mb-1" style="font-size: 0.8rem;">
                                {{ $product->category->getTranslation('name', app()->getLocale()) }}
                            </small>
                        @endif

                        {{-- Məhsul Adı (Link əlavə edildi) --}}
                        <h6 class="card-title fw-bold text-truncate mb-3">
                            <a href="{{ route('shop.show', $product->slug) }}" class="text-dark text-decoration-none">
                                {{ $product->getTranslation('name', app()->getLocale()) }}
                            </a>
                        </h6>

                        {{-- Qiymət --}}
                        <div class="mt-auto d-flex align-items-center justify-content-between">
                            <div class="price-box">
                                @if($product->sale_price)
                                    <span class="text-danger fw-bold fs-5">{{ $product->sale_price }} ₼</span>
                                    <small class="text-muted text-decoration-line-through ms-1">{{ $product->price }} ₼</small>
                                @else
                                    <span class="text-dark fw-bold fs-5">{{ $product->price }} ₼</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- Məhsul tapılmadıqda --}}
            <div class="col-12 text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-box-open text-muted fa-4x"></i>
                </div>
                <h3 class="text-muted">{{ __('shop.no_results', ['default' => 'Nəticə tapılmadı']) }}</h3>
                <p class="text-muted">{{ __('shop.no_results_desc', ['default' => 'Axtarışınıza uyğun məhsul yoxdur.']) }}</p>
                <a href="{{ route('shop') }}" class="btn btn-outline-primary rounded-pill mt-2">{{ __('shop.show_all', ['default' => 'Bütün Məhsulları Göstər']) }}</a>
            </div>
        @endforelse
    </div>

    {{-- Pagination (Səhifələmə) --}}
    @if($products->hasPages())
        <div class="d-flex justify-content-center mt-5 custom-pagination">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

{{-- FLOATING CART BUTTON (Üzən Səbət Düyməsi) --}}
<a href="{{ route('cart.index') }}" class="floating-cart-btn shadow-lg" title="{{ __('shop.cart', ['default' => 'Səbət']) }}">
    <i class="fas fa-shopping-cart"></i>
    @if(session('cart') && count(session('cart')) > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
            {{ count(session('cart')) }}
        </span>
    @endif
</a>

{{-- Səhifədaxili CSS --}}
<style>
    .object-fit-cover {
        object-fit: cover;
    }
    /* Məhsul kartına hover effekti */
    .product-card:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
    }
    /* Hover zamanı düymələri göstər */
    .product-card:hover .product-actions {
        top: 0 !important;
    }

    /* Pagination Dizaynı (Yuvarlaq və Modern) */
    .custom-pagination .page-link {
        border-radius: 50px !important;
        margin: 0 5px;
        border: none;
        color: #333;
        font-weight: 600;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .custom-pagination .page-item.active .page-link {
        background-color: #0d6efd;
        color: white;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
    }
    .custom-pagination .page-link:hover {
        background-color: #e9ecef;
        color: #0d6efd;
    }

    /* Floating Cart Button Style */
    .floating-cart-btn {
        position: fixed;
        bottom: 30px; /* PC üçün default */
        right: 30px;
        width: 60px;
        height: 60px;
        background-color: #0d6efd;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        z-index: 1060; /* Navbar-dan yuxarıda olması üçün */
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .floating-cart-btn:hover {
        transform: scale(1.1);
        color: white;
        box-shadow: 0 10px 20px rgba(13, 110, 253, 0.4) !important;
    }

    /* Mobil üçün tənzimləmə */
    @media (max-width: 991.98px) {
        .floating-cart-btn {
            bottom: 90px; /* Mobil navbar (70px) + boşluq (20px) */
            right: 20px;
            width: 50px;
            height: 50px;
            font-size: 20px;
        }
    }
</style>
@endsection
