@extends('layouts.public')

@section('title', __('shop.cart_title', ['default' => 'Səbət']))

@section('content')

<div class="container py-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">{{ __('home.home', ['default' => 'Ana Səhifə']) }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop') }}" class="text-decoration-none">{{ __('home.shop', ['default' => 'Mağaza']) }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('shop.cart', ['default' => 'Səbət']) }}</li>
        </ol>
    </nav>

    <h2 class="fw-bold mb-4">{{ __('shop.your_cart', ['default' => 'Sizin Səbətiniz']) }}</h2>

    @if(session('cart') && count(session('cart')) > 0)
        <div class="row g-5">
            {{-- Məhsullar Cədvəli --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 ps-4">{{ __('shop.product', ['default' => 'Məhsul']) }}</th>
                                    <th class="border-0 py-3">{{ __('shop.price', ['default' => 'Qiymət']) }}</th>
                                    <th class="border-0 py-3">{{ __('shop.quantity', ['default' => 'Say']) }}</th>
                                    <th class="border-0 py-3">{{ __('shop.subtotal', ['default' => 'Cəm']) }}</th>
                                    <th class="border-0 py-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach(session('cart') as $id => $details)
                                    @php $total += $details['price'] * $details['quantity']; @endphp
                                    <tr data-id="{{ $id }}">
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $details['image'] }}" class="rounded me-3 object-fit-cover" style="width: 60px; height: 60px;" alt="{{ $details['name'] }}">
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark">{{ $details['name'] }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-muted">{{ $details['price'] }} ₼</td>
                                        <td>
                                            <input type="number" value="{{ $details['quantity'] }}" class="form-control form-control-sm text-center quantity update-cart" style="width: 70px;" min="1">
                                        </td>
                                        <td class="fw-bold text-primary">{{ $details['price'] * $details['quantity'] }} ₼</td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-light text-danger rounded-circle remove-from-cart" title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Cəm və Checkout --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-light">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">{{ __('shop.cart_totals', ['default' => 'Səbət Cəmi']) }}</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">{{ __('shop.subtotal', ['default' => 'Ara Cəm']) }}</span>
                            <span class="fw-bold">{{ $total }} ₼</span>
                        </div>
                        <div class="d-flex justify-content-between mb-4 border-bottom pb-3">
                            <span class="text-muted">{{ __('shop.shipping', ['default' => 'Çatdırılma']) }}</span>
                            <span class="text-success fw-bold">{{ __('shop.free', ['default' => 'Pulsuz']) }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5 fw-bold">{{ __('shop.total', ['default' => 'Yekun']) }}</span>
                            <span class="h5 fw-bold text-primary">{{ $total }} ₼</span>
                        </div>

                        <a href="#" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm">
                            {{ __('shop.checkout', ['default' => 'Sifarişi Rəsmiləşdir']) }} <i class="fas fa-arrow-right ms-2"></i>
                        </a>

                        <a href="{{ route('shop') }}" class="btn btn-link text-muted text-decoration-none w-100 mt-2">
                            <i class="fas fa-arrow-left me-1"></i> {{ __('shop.continue_shopping', ['default' => 'Alış-verişə davam et']) }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-shopping-basket fa-4x text-muted opacity-25"></i>
            </div>
            <h3 class="text-muted mb-3">{{ __('shop.cart_empty', ['default' => 'Səbətiniz boşdur']) }}</h3>
            <p class="text-muted mb-4">{{ __('shop.cart_empty_desc', ['default' => 'Hələ heç bir məhsul əlavə etməmisiniz.']) }}</p>
            <a href="{{ route('shop') }}" class="btn btn-primary rounded-pill px-5 py-2">
                {{ __('shop.go_shop', ['default' => 'Mağazaya Keç']) }}
            </a>
        </div>
    @endif
</div>

{{-- AJAX Scriptləri (Update & Remove) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Məhsul sayını dəyişəndə
    $(".update-cart").change(function (e) {
        e.preventDefault();
        var ele = $(this);
        var quantity = ele.val();

        // Minimum 1 olmalıdır
        if(quantity < 1) {
            ele.val(1);
            return;
        }

        $.ajax({
            url: '{{ route('cart.update') }}',
            method: "PATCH",
            data: {
                _token: '{{ csrf_token() }}',
                id: ele.parents("tr").attr("data-id"),
                quantity: quantity
            },
            success: function (response) {
                window.location.reload();
            }
        });
    });

    // Məhsulu siləndə
    $(".remove-from-cart").click(function (e) {
        e.preventDefault();
        var ele = $(this);

        if(confirm("Bu məhsulu səbətdən silmək istədiyinizə əminsiniz?")) {
            $.ajax({
                url: '{{ route('cart.remove') }}',
                method: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: ele.parents("tr").attr("data-id")
                },
                success: function (response) {
                    window.location.reload();
                }
            });
        }
    });
</script>

<style>
    .breadcrumb-item a { color: #6c757d; }
    .breadcrumb-item.active { color: #0d6efd; font-weight: 600; }
    /* Chrome/Safari/Edge-də input type number oxlarını gizlət */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
@endsection
