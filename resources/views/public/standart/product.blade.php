@extends('layouts.public')

@section('title', $page->getTranslation('title', app()->getLocale()))

@section('content')

<div class="container py-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">{{ __('home.home', ['default' => 'Ana Səhifə']) }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop') }}" class="text-decoration-none">{{ __('home.shop', ['default' => 'Mağaza']) }}</a></li>
            @if($product->category)
                <li class="breadcrumb-item">
                    <a href="{{ route('shop', ['category' => $product->category->id]) }}" class="text-decoration-none">
                        {{ $product->category->getTranslation('name', app()->getLocale()) }}
                    </a>
                </li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ $product->getTranslation('name', app()->getLocale()) }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        {{-- SOL: Məhsul Şəkli --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="position-relative bg-light text-center p-4">
                    @if($product->sale_price)
                        <div class="position-absolute top-0 start-0 m-3 z-1">
                            <span class="badge bg-danger rounded-pill px-3 py-2 fs-6 shadow-sm">
                                -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                            </span>
                        </div>
                    @endif

                    <img src="{{ $product->getFirstMediaUrl('products') ?: asset('assets/img/no-image.png') }}"
                         class="img-fluid object-fit-contain"
                         style="max-height: 500px;"
                         alt="{{ $product->getTranslation('name', app()->getLocale()) }}">
                </div>
            </div>
        </div>

        {{-- SAĞ: Məhsul Məlumatları --}}
        <div class="col-lg-6">
            <div class="ps-lg-4">
                {{-- Kateqoriya --}}
                @if($product->category)
                    <span class="badge bg-primary-subtle text-primary mb-2 px-3 rounded-pill">
                        {{ $product->category->getTranslation('name', app()->getLocale()) }}
                    </span>
                @endif

                <h1 class="fw-bold mb-3">{{ $product->getTranslation('name', app()->getLocale()) }}</h1>

                {{-- Stok Statusu --}}
                <div class="mb-3">
                    @if((int)$product->stock_quantity > 0)
                        <span class="text-success small fw-bold">
                            <i class="fas fa-check-circle me-1"></i>
                            {{ __('shop.in_stock', ['default' => 'Stokda var']) }} ({{ $product->stock_quantity }} {{ __('shop.items_count', ['default' => 'ədəd']) }})
                        </span>
                    @else
                        <span class="text-danger small fw-bold">
                            <i class="fas fa-times-circle me-1"></i>
                            {{ __('shop.out_of_stock', ['default' => 'Bitib']) }}
                        </span>
                    @endif
                </div>

                {{-- Qiymət --}}
                <div class="mb-4">
                    @if($product->sale_price)
                        <span class="text-danger fw-bold display-6">{{ $product->sale_price }} ₼</span>
                        <span class="text-muted text-decoration-line-through fs-5 ms-2">{{ $product->price }} ₼</span>
                    @else
                        <span class="text-dark fw-bold display-6">{{ $product->price }} ₼</span>
                    @endif
                </div>

                {{-- Qısa Təsvir (əgər varsa) --}}
                <p class="text-muted mb-4" style="line-height: 1.8;">
                    {{ Str::limit(strip_tags($product->getTranslation('description', app()->getLocale())), 200) }}
                </p>

                {{-- Düymələr --}}
                <div class="d-flex gap-3 mb-5">
                    {{-- Səbətə At Formu --}}
                    <form action="{{ route('cart.add') }}" method="POST" class="flex-grow-1">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 w-100 shadow-sm btn-hover-scale" {{ (int)$product->stock_quantity <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart me-2"></i> {{ __('shop.add_to_cart', ['default' => 'Səbətə At']) }}
                        </button>
                    </form>

                    <button class="btn btn-outline-success btn-lg rounded-circle" style="width: 50px; height: 50px; padding: 0;">
                        <i class="fab fa-whatsapp fa-lg"></i>
                    </button>
                </div>

                {{-- Əlavə Məlumatlar --}}
                <div class="card bg-light border-0 rounded-3">
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-2">
                                <i class="fas fa-truck text-muted me-2"></i>
                                {{ __('shop.delivery', ['default' => 'Çatdırılma']) }}: <strong>{{ __('shop.delivery_time', ['default' => '24 saat ərzində']) }}</strong>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-shield-alt text-muted me-2"></i>
                                {{ __('shop.warranty', ['default' => 'Zəmanət']) }}: <strong>{{ __('shop.original_product', ['default' => 'Orijinal məhsul']) }}</strong>
                            </li>
                            <li>
                                <i class="fas fa-undo text-muted me-2"></i>
                                {{ __('shop.returns', ['default' => 'Qaytarılma']) }}: <strong>{{ __('shop.return_policy', ['default' => '14 gün ərzində']) }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs: Təsvir və Rəylər --}}
    <div class="row mt-5 pt-4">
        <div class="col-12">
            <ul class="nav nav-tabs border-bottom-0 mb-3" id="productTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-bold px-4 border-0 border-bottom border-primary border-3" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc" type="button">
                        {{ __('shop.product_about', ['default' => 'Məhsul Haqqında']) }}
                    </button>
                </li>
            </ul>
            <div class="tab-content bg-white p-4 rounded-4 shadow-sm border" id="productTabsContent">
                <div class="tab-pane fade show active" id="desc" role="tabpanel">
                    <div class="text-muted" style="line-height: 1.8;">
                        {!! $product->getTranslation('description', app()->getLocale()) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

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

<style>
    .btn-hover-scale:hover { transform: scale(1.02); transition: transform 0.2s; }
    .breadcrumb-item a { color: #6c757d; }
    .breadcrumb-item.active { color: #0d6efd; font-weight: 600; }

    /* Floating Cart Button Style (Common) */
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
