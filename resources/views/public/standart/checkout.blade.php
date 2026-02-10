@extends('layouts.public')

@section('title', $page->getTranslation('title', app()->getLocale()))

@section('content')

<div class="container py-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">{{ __('home.home', ['default' => 'Ana Səhifə']) }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop') }}" class="text-decoration-none">{{ __('home.shop', ['default' => 'Mağaza']) }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none">{{ __('shop.cart', ['default' => 'Səbət']) }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('shop.checkout', ['default' => 'Sifarişi Rəsmiləşdir']) }}</li>
        </ol>
    </nav>

    <h2 class="fw-bold mb-4">{{ __('shop.checkout_title', ['default' => 'Sifarişi Tamamla']) }}</h2>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('checkout.submit') }}" method="POST">
        @csrf
        <div class="row g-5">

            {{-- SOL: Çatdırılma Məlumatları --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-shipping-fast me-2 text-primary"></i> {{ __('shop.billing_details', ['default' => 'Çatdırılma Məlumatları']) }}</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('shop.name', ['default' => 'Ad']) }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ Auth::check() ? Auth::user()->name : old('name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('shop.surname', ['default' => 'Soyad']) }}</label>
                                <input type="text" class="form-control" name="surname" value="{{ Auth::check() ? Auth::user()->surname : old('surname') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('shop.email', ['default' => 'E-poçt']) }} <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{ Auth::check() ? Auth::user()->email : old('email') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('shop.phone', ['default' => 'Telefon']) }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone" value="{{ Auth::check() ? Auth::user()->phone : old('phone') }}" placeholder="050 000 00 00" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">{{ __('shop.address', ['default' => 'Ünvan']) }} <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="address" rows="2" placeholder="Küçə, Bina, Mənzil..." required>{{ old('address') }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">{{ __('shop.note', ['default' => 'Sifariş qeydi (Opsional)']) }}</label>
                                <textarea class="form-control" name="note" rows="2" placeholder="Kuryer üçün xüsusi qeydlər...">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ödəmə Üsulu --}}
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-credit-card me-2 text-success"></i> {{ __('shop.payment_method', ['default' => 'Ödəmə Üsulu']) }}</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash" checked>
                            <label class="form-check-label fw-bold" for="payment_cash">
                                <i class="fas fa-money-bill-wave me-2 text-success"></i> {{ __('shop.cash_on_delivery', ['default' => 'Qapıda Nağd Ödəniş']) }}
                            </label>
                            <div class="text-muted small ms-4">Sifarişi təhvil alarkən kuryerə nağd ödəniş edin.</div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_card" value="card" disabled>
                            <label class="form-check-label fw-bold text-muted" for="payment_card">
                                <i class="fas fa-credit-card me-2"></i> {{ __('shop.card_payment', ['default' => 'Kartla Ödəniş (Tezliklə)']) }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SAĞ: Sifariş Xülasəsi --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-light">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h5 class="mb-0 fw-bold">{{ __('shop.order_summary', ['default' => 'Sifariş Xülasəsi']) }}</h5>
                    </div>
                    <div class="card-body p-4">
                        @php $total = 0; @endphp
                        @if(session('cart'))
                            <div class="mb-3" style="max-height: 300px; overflow-y: auto;">
                                @foreach(session('cart') as $id => $details)
                                    @php $total += $details['price'] * $details['quantity']; @endphp
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0 position-relative">
                                            <img src="{{ $details['image'] }}" class="rounded border" style="width: 50px; height: 50px; object-fit: cover;">
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                                                {{ $details['quantity'] }}
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0 small fw-bold text-truncate" style="max-width: 150px;">{{ $details['name'] }}</h6>
                                            <small class="text-muted">{{ $details['price'] }} ₼</small>
                                        </div>
                                        <div class="text-end fw-bold">
                                            {{ $details['price'] * $details['quantity'] }} ₼
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <hr>

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
                            <span class="h4 fw-bold text-primary">{{ $total }} ₼</span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm">
                            {{ __('shop.place_order', ['default' => 'Sifarişi Təsdiqlə']) }} <i class="fas fa-check-circle ms-2"></i>
                        </button>

                        <a href="{{ route('cart.index') }}" class="btn btn-link text-muted text-decoration-none w-100 mt-2">
                            <i class="fas fa-edit me-1"></i> {{ __('shop.edit_cart', ['default' => 'Səbətə qayıt']) }}
                        </a>
                    </div>
                </div>

                {{-- Güvənlik Nişanları --}}
                <div class="text-center mt-4 text-muted small">
                    <i class="fas fa-lock me-1"></i> {{ __('shop.secure_payment', ['default' => 'Təhlükəsiz və Gizli Ödəniş']) }}
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
