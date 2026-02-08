@extends('layouts.public')

@section('title', $page->getTranslation('title', app()->getLocale()))

@section('content')

<div class="container py-5">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">{{ __('home.home', ['default' => 'Ana Səhifə']) }}</a></li>
            <li class="breadcrumb-item text-muted">Xidmətlər</li> {{-- Gələcəkdə xidmət siyahısı olsa bura link veriləcək --}}
            <li class="breadcrumb-item active text-primary" aria-current="page">{{ $service->getTranslation('name', app()->getLocale()) }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        {{-- SOL: Xidmət Şəkli --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                @if($service->image)
                    <img src="{{ asset($service->image) }}" class="img-fluid w-100 h-100 object-fit-cover"
                         style="min-height: 400px;"
                         alt="{{ $service->getTranslation('name', app()->getLocale()) }}">
                @else
                    <div class="bg-light w-100 h-100 d-flex align-items-center justify-content-center text-muted" style="min-height: 400px;">
                        <i class="fas fa-stethoscope fa-4x opacity-25"></i>
                    </div>
                @endif
            </div>
        </div>

        {{-- SAĞ: Məlumatlar --}}
        <div class="col-lg-6">
            <div class="ps-lg-4">
                <h1 class="fw-bold mb-3 text-dark">{{ $service->getTranslation('name', app()->getLocale()) }}</h1>

                {{-- Qiymət --}}
                <div class="mb-4">
                    @if($service->price)
                        <span class="display-6 fw-bold text-primary">{{ $service->price }} ₼</span>
                    @else
                        <span class="display-6 fw-bold text-success">{{ __('service.negotiable', ['default' => 'Razılaşma ilə']) }}</span>
                    @endif
                </div>

                {{-- Qısa Təsvir --}}
                <p class="text-muted fs-5 mb-4" style="line-height: 1.6;">
                    {{ $service->getTranslation('short_description', app()->getLocale()) }}
                </p>

                {{-- Xüsusiyyətlər / Daxil Olanlar --}}
                @if($service->getTranslation('features', app()->getLocale()))
                    <div class="card bg-primary-subtle border-0 rounded-4 mb-4">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-check-circle me-2"></i> {{ __('service.included', ['default' => 'Xidmətə daxildir:']) }}
                            </h6>
                            <div class="text-dark opacity-75">
                                {!! nl2br(e($service->getTranslation('features', app()->getLocale()))) !!}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Sifariş Düyməsi --}}
                <div class="d-grid">
                    <button class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm py-3" data-bs-toggle="modal" data-bs-target="#orderModal">
                        {{ __('service.order_now', ['default' => 'Xidməti Sifariş Et']) }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Detallı Məlumat (Aşağıda) --}}
    @if($service->getTranslation('description', app()->getLocale()))
        <div class="row mt-5 pt-4">
            <div class="col-12">
                <h3 class="fw-bold mb-4">{{ __('service.details', ['default' => 'Detallı Məlumat']) }}</h3>
                <div class="bg-light p-4 p-md-5 rounded-4 text-muted fs-6" style="line-height: 1.8;">
                    {!! $service->getTranslation('description', app()->getLocale()) !!}
                </div>
            </div>
        </div>
    @endif

</div>

{{-- SİFARİŞ MODALI --}}
<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-center w-100">{{ __('service.contact_method', ['default' => 'Əlaqə Vasitəsini Seçin']) }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-2 text-center">
                <p class="text-muted mb-4">{{ __('service.contact_desc', ['default' => 'Bu xidməti sifariş etmək üçün bizimlə birbaşa əlaqə saxlaya bilərsiniz.']) }}</p>

                <div class="d-grid gap-3">
                    {{-- WhatsApp Linki Hazırlanması --}}
                    @php
                        $phone = $settings->phone ?? '';
                        $cleanPhone = preg_replace('/[^0-9]/', '', $phone); // Yalnız rəqəmlər
                        $serviceName = $service->getTranslation('name', app()->getLocale());
                        $message = "Salam, mən bu xidməti sifariş etmək istəyirəm: " . $serviceName;
                        $whatsappUrl = "https://wa.me/" . $cleanPhone . "?text=" . urlencode($message);
                    @endphp

                    {{-- WhatsApp Düyməsi --}}
                    <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-success btn-lg rounded-pill d-flex align-items-center justify-content-center gap-2 shadow-sm">
                        <i class="fab fa-whatsapp fa-lg"></i>
                        <span>{{ __('service.via_whatsapp', ['default' => 'WhatsApp ilə Yazın']) }}</span>
                    </a>

                    {{-- Zəng Düyməsi --}}
                    <a href="tel:{{ $cleanPhone }}" class="btn btn-outline-primary btn-lg rounded-pill d-flex align-items-center justify-content-center gap-2">
                        <i class="fas fa-phone-alt"></i>
                        <span>{{ __('service.call_now', ['default' => 'Zəng Edin']) }}</span>
                    </a>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <small class="text-muted">
                        <i class="far fa-clock me-1"></i> {{ __('service.working_hours', ['default' => 'Qısa müddətdə geri dönüş ediləcək.']) }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Daxil olanlar siyahısı üçün sadə stil */
    .card-body ul { padding-left: 1rem; margin-bottom: 0; }
    .card-body li { margin-bottom: 0.5rem; }
</style>

@endsection
