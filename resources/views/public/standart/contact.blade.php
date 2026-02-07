@extends('layouts.public')

@section('title', $page->getTranslation('title', app()->getLocale()))

@section('content')

{{-- 1. HERO SECTION --}}
<section class="bg-primary text-white py-5 position-relative overflow-hidden">
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10"
         style="background-image: url('https://img.freepik.com/free-photo/contact-us-communication-support-service-assistance-concept_53876-128103.jpg'); background-size: cover; background-position: center;">
    </div>
    <div class="container position-relative z-1 text-center">
        <h1 class="fw-bold display-5">{{ $page->getTranslation('title', app()->getLocale()) }}</h1>
        <p class="lead opacity-75 mb-0">{{ __('home.contact_subtitle', ['default' => 'Bizimlə əlaqə saxlamaqdan çəkinməyin']) }}</p>
    </div>
</section>

{{-- 2. ƏLAQƏ MƏLUMATLARI VƏ FORMA --}}
<div class="container py-5">
    <div class="row g-5">

        {{-- SOL TƏRƏF: Əlaqə Məlumatları --}}
        <div class="col-lg-5">
            <div class="pe-lg-4">
                <h3 class="fw-bold mb-4">{{ __('home.contact_info', ['default' => 'Əlaqə Məlumatları']) }}</h3>
                <p class="text-muted mb-4">
                    {{ __('home.contact_desc', ['default' => 'Sualınız var? Aşağıdakı vasitələrlə bizimlə əlaqə saxlaya və ya form vasitəsilə mesaj göndərə bilərsiniz.']) }}
                </p>

                <div class="d-flex mb-4">
                    <div class="flex-shrink-0 btn-square bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fas fa-map-marker-alt fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="fw-bold mb-1">{{ __('home.address', ['default' => 'Ünvan']) }}</h6>
                        <p class="text-muted mb-0">
                            {{ $settings->getTranslation('address', app()->getLocale()) ?? 'Ünvan qeyd olunmayıb' }}
                        </p>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="flex-shrink-0 btn-square bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fas fa-phone fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="fw-bold mb-1">{{ __('home.phone', ['default' => 'Telefon']) }}</h6>
                        <p class="text-muted mb-0">
                            <a href="tel:{{ $settings->phone }}" class="text-decoration-none text-muted">{{ $settings->phone ?? '+994 00 000 00 00' }}</a>
                        </p>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="flex-shrink-0 btn-square bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fas fa-envelope fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="fw-bold mb-1">{{ __('home.email', ['default' => 'E-poçt']) }}</h6>
                        <p class="text-muted mb-0">
                            <a href="mailto:{{ $settings->email }}" class="text-decoration-none text-muted">{{ $settings->email ?? 'info@example.com' }}</a>
                        </p>
                    </div>
                </div>

                {{-- Sosial Media Linkləri --}}
                @if(!empty($settings->social_links))
                    <h6 class="fw-bold mt-5 mb-3">{{ __('home.social_media', ['default' => 'Bizi izləyin']) }}</h6>
                    <div class="d-flex gap-2">
                        @foreach($settings->social_links as $key => $link)
                            @if(!empty($link))
                                @php
                                    $icon = match($key) {
                                        'facebook' => 'fab fa-facebook-f',
                                        'instagram' => 'fab fa-instagram',
                                        'twitter' => 'fab fa-twitter',
                                        'whatsapp' => 'fab fa-whatsapp',
                                        'youtube' => 'fab fa-youtube',
                                        default => 'fas fa-link'
                                    };
                                    $color = match($key) {
                                        'facebook' => '#3b5998',
                                        'instagram' => '#E1306C',
                                        'twitter' => '#1DA1F2',
                                        'whatsapp' => '#25D366',
                                        'youtube' => '#FF0000',
                                        default => '#333'
                                    };
                                @endphp
                                <a href="{{ $link }}" target="_blank" class="btn btn-outline-light text-dark border shadow-sm rounded-circle d-flex align-items-center justify-content-center social-btn"
                                   style="width: 40px; height: 40px; transition: all 0.3s;"
                                   onmouseover="this.style.backgroundColor='{{ $color }}'; this.style.color='white'; this.style.borderColor='{{ $color }}';"
                                   onmouseout="this.style.backgroundColor='transparent'; this.style.color='#333'; this.style.borderColor='#dee2e6';">
                                    <i class="{{ $icon }}"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- SAĞ TƏRƏF: Mesaj Formu --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">
                <h3 class="fw-bold mb-4">{{ __('home.send_message', ['default' => 'Bizə Yazın']) }}</h3>

                {{-- Form Action sonra route('contact.send') olacaq --}}
                <form action="#" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-light border-0" id="name" name="name" placeholder="Adınız" required>
                                <label for="name">{{ __('home.name', ['default' => 'Adınız']) }}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-light border-0" id="surname" name="surname" placeholder="Soyadınız">
                                <label for="surname">{{ __('home.surname', ['default' => 'Soyadınız']) }}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control bg-light border-0" id="email" name="email" placeholder="E-poçt" required>
                                <label for="email">{{ __('home.email', ['default' => 'E-poçt']) }}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-light border-0" id="phone" name="phone" placeholder="Telefon">
                                <label for="phone">{{ __('home.phone', ['default' => 'Telefon']) }}</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control bg-light border-0" id="message" name="message" placeholder="Mesajınız" style="height: 150px" required></textarea>
                                <label for="message">{{ __('home.message', ['default' => 'Mesajınız']) }}</label>
                            </div>
                        </div>
                        <div class="col-12 text-end">
                            <button class="btn btn-primary btn-lg rounded-pill px-5" type="submit">
                                {{ __('home.send_btn', ['default' => 'Göndər']) }} <i class="fas fa-paper-plane ms-2"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- 3. GOOGLE MAP (Tam Ekran) --}}
@if($settings->map_iframe)
    <section class="map-section w-100">
        <div class="ratio ratio-21x9" style="min-height: 400px;">
            {!! $settings->map_iframe !!}
        </div>
    </section>
@endif

@endsection
