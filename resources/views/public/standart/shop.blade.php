@extends('layouts.public')

@section('title', $page->getTranslation('title', app()->getLocale()))

@section('content')
<div class="container py-5">

    {{-- Başlıq və Kateqoriyalar --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="fw-bold mb-0">{{ $page->getTranslation('title', app()->getLocale()) }}</h1>
            <p class="text-muted mt-1">{{ $products->total() }} məhsul mövcuddur</p>
        </div>

        {{-- Kateqoriya Filtrləri (Sadə) --}}
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="d-inline-block">
                <a href="{{ route('shop') }}" class="btn btn-sm btn-dark rounded-pill px-3 m-1">Hamısı</a>
                @foreach($categories as $category)
                    <a href="#" class="btn btn-sm btn-outline-secondary rounded-pill px-3 m-1">
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
                        <img src="{{ $product->getFirstMediaUrl('products') ?: asset('assets/img/no-image.png') }}"
                             class="card-img-top w-100 h-100 object-fit-cover"
                             alt="{{ $product->getTranslation('name', app()->getLocale()) }}">

                        {{-- Hover Actions --}}
                        <div class="product-actions position-absolute start-0 w-100 h-100 d-flex justify-content-center align-items-center"
                             style="background: rgba(0,0,0,0.4); top: 100%; transition: top 0.3s;">
                            {{-- Ətraflı düyməsi --}}
                            {{-- Route sonra order.show olaraq dəyişdiriləcək --}}
                            <a href="#" class="btn btn-light rounded-circle mx-2" title="Ətraflı Bax">
                                <i class="fas fa-eye"></i>
                            </a>
                            {{-- Səbət düyməsi --}}
                            <button class="btn btn-primary rounded-circle mx-2" title="Səbətə At">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Məlumat Hissəsi --}}
                    <div class="card-body d-flex flex-column">
                        {{-- Kateqoriya --}}
                        @if($product->category)
                            <small class="text-muted mb-1" style="font-size: 0.8rem;">
                                {{ $product->category->getTranslation('name', app()->getLocale()) }}
                            </small>
                        @endif

                        {{-- Ad --}}
                        <h6 class="card-title fw-bold text-truncate mb-3">
                            <a href="#" class="text-dark text-decoration-none">
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
            {{-- Məhsul Yoxdursa --}}
            <div class="col-12 text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-box-open text-muted fa-4x"></i>
                </div>
                <h3 class="text-muted">Hələlik məhsul yoxdur</h3>
                <p class="text-muted">Zəhmət olmasa daha sonra yoxlayın.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $products->links() }}
        </div>
    @endif
</div>

{{-- Səhifədaxili Style --}}
<style>
    .object-fit-cover {
        object-fit: cover;
    }
    .product-card:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
    }
    .product-card:hover .product-actions {
        top: 0 !important;
    }
</style>
@endsection
