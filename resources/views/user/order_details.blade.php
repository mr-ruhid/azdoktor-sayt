@extends('layouts.public')

@section('title', $page->getTranslation('title', app()->getLocale()))

@section('content')

<div class="container py-5">

    {{-- Başlıq və Geri Düyməsi --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0 text-dark">
            {{ __('user.order_details_title', ['default' => 'Sifariş Detalları']) }}
            <span class="text-primary">#{{ $order->order_number }}</span>
        </h2>
        <a href="{{ route('user.dashboard') }}#orders" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i> {{ __('user.back_to_orders', ['default' => 'Sifarişlərə Qayıt']) }}
        </a>
    </div>

    <div class="row g-4">

        {{-- SOL: Məhsullar Siyahısı --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">{{ __('user.items', ['default' => 'Məhsullar']) }}</h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 border-0 py-3">{{ __('user.product', ['default' => 'Məhsul']) }}</th>
                                <th class="border-0 py-3 text-center">{{ __('user.quantity', ['default' => 'Say']) }}</th>
                                <th class="border-0 py-3 text-end pe-4">{{ __('user.total', ['default' => 'Cəm']) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            {{-- Məhsul şəklini tapmaq üçün polimorfik əlaqədən istifadə edirik --}}
                                            @php
                                                $image = asset('assets/img/no-image.png');
                                                if($item->orderable && method_exists($item->orderable, 'getFirstMediaUrl')) {
                                                    $imgUrl = $item->orderable->getFirstMediaUrl('products');
                                                    if($imgUrl) $image = $imgUrl;
                                                }
                                            @endphp
                                            <img src="{{ $image }}" class="rounded me-3 object-fit-cover border" style="width: 50px; height: 50px;" alt="{{ $item->name }}">
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark">{{ $item->name }}</h6>
                                                <small class="text-muted">{{ $item->price }} ₼</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                    <td class="text-end pe-4 fw-bold text-primary">{{ $item->total }} ₼</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="2" class="text-end fw-bold py-3">{{ __('user.subtotal', ['default' => 'Ara Cəm:']) }}</td>
                                <td class="text-end fw-bold py-3 pe-4">{{ $order->subtotal }} ₼</td>
                            </tr>
                            @if($order->discount > 0)
                                <tr>
                                    <td colspan="2" class="text-end fw-bold py-3 text-danger">{{ __('user.discount', ['default' => 'Endirim:']) }}</td>
                                    <td class="text-end fw-bold py-3 pe-4 text-danger">-{{ $order->discount }} ₼</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="2" class="text-end h5 fw-bold py-3 text-dark">{{ __('user.total_amount', ['default' => 'Yekun Məbləğ:']) }}</td>
                                <td class="text-end h5 fw-bold py-3 pe-4 text-primary">{{ $order->total }} ₼</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- SAĞ: Sifariş Məlumatları --}}
        <div class="col-lg-4">

            {{-- Sifariş Statusu --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">{{ __('user.order_status', ['default' => 'Sifariş Statusu']) }}</h5>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">{{ __('user.status', ['default' => 'Status:']) }}</span>
                        <span class="badge {{ $order->status_badge }} fs-6">{{ $order->status_label }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">{{ __('user.date', ['default' => 'Tarix:']) }}</span>
                        <span class="fw-bold">{{ $order->created_at->format('d.m.Y H:i') }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">{{ __('user.payment', ['default' => 'Ödəniş:']) }}</span>
                        <span class="fw-bold text-uppercase">{{ $order->payment_method == 'cash' ? __('user.cash', ['default' => 'Nəğd']) : __('user.card', ['default' => 'Kart']) }}</span>
                    </div>
                </div>
            </div>

            {{-- Çatdırılma Məlumatları --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">{{ __('user.delivery_info', ['default' => 'Çatdırılma Məlumatları']) }}</h5>

                    <div class="mb-3">
                        <small class="text-muted d-block">{{ __('user.customer', ['default' => 'Ad Soyad:']) }}</small>
                        <span class="fw-bold">{{ $order->customer_name }}</span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">{{ __('user.phone', ['default' => 'Telefon:']) }}</small>
                        <span class="fw-bold">{{ $order->customer_phone }}</span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">{{ __('user.email', ['default' => 'E-poçt:']) }}</small>
                        <span>{{ $order->customer_email }}</span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">{{ __('user.address', ['default' => 'Ünvan:']) }}</small>
                        <span>{{ $order->customer_address }}</span>
                    </div>

                    @if($order->note)
                        <div class="p-3 bg-light rounded border mt-3">
                            <small class="text-muted d-block mb-1">{{ __('user.note', ['default' => 'Qeyd:']) }}</small>
                            <span class="fst-italic text-secondary">"{{ $order->note }}"</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
