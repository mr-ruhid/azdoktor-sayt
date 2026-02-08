@extends('layouts.public')

@section('title', $page->getTranslation('title', app()->getLocale()))

@section('content')

{{-- 1. HERO & AXTARIŞ HİSSƏSİ --}}
<section class="hero-section position-relative py-4 mb-0 d-flex align-items-center" style="min-height: 480px;">
    {{-- Arxa Fon --}}
    @php
        $bannerImage = $page->getMeta('banner_image') ? asset($page->getMeta('banner_image')) : 'https://img.freepik.com/free-photo/blur-hospital_1203-7957.jpg';
    @endphp
    <div class="position-absolute top-0 start-0 w-100 h-100"
         style="background: url('{{ $bannerImage }}') no-repeat center center/cover;">
         {{-- Qradiyent Overlay --}}
         <div class="position-absolute top-0 start-0 w-100 h-100"
              style="background: linear-gradient(135deg, rgba(13, 110, 253, 0.9) 0%, rgba(13, 202, 240, 0.75) 100%);"></div>
    </div>

    <div class="container position-relative z-2">
        <div class="row justify-content-center text-center mb-4">
            <div class="col-lg-9">
                <h1 class="fw-bold display-5 text-white mb-2 tracking-tight animate-fade-in">
                    {{ $page->getTranslation('title', app()->getLocale()) }}
                </h1>
                <p class="lead text-white opacity-90 fs-6 mb-0">
                    {{ $page->getTranslation('content', app()->getLocale()) }}
                </p>
            </div>
        </div>

        {{-- AXTARIŞ FORMU (Glassmorphism Effect) --}}
        <div class="search-box p-4 rounded-4 shadow-lg animate-slide-up">
            <form action="#" method="GET">
                <div class="row g-3">
                    {{-- Həkim Adı --}}
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label text-white small fw-bold mb-1 ms-1">{{ __('home.doctor_name_label', ['default' => 'Həkim Adı']) }}</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-0 text-primary"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control border-0 fs-6" placeholder="{{ __('home.search_doctor_placeholder', ['default' => 'Həkim adı axtar...']) }}">
                        </div>
                    </div>

                    {{-- İxtisas --}}
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label text-white small fw-bold mb-1 ms-1">{{ __('home.specialty_label', ['default' => 'İxtisas']) }}</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-0 text-primary"><i class="fas fa-stethoscope"></i></span>
                            <select class="form-select border-0 fs-6 cursor-pointer">
                                <option value="">{{ __('home.all_specialties', ['default' => 'Bütün İxtisaslar']) }}</option>
                                @foreach($specialties as $specialty)
                                    <option value="{{ $specialty->id }}">
                                        {{ $specialty->getTranslation('name', app()->getLocale()) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Klinika --}}
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label text-white small fw-bold mb-1 ms-1">{{ __('home.clinic_label', ['default' => 'Klinika']) }}</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-0 text-primary"><i class="fas fa-hospital"></i></span>
                            <select class="form-select border-0 fs-6 cursor-pointer">
                                <option value="">{{ __('home.select_clinic', ['default' => 'Klinika Seçin']) }}</option>
                                @foreach($clinics as $clinic)
                                    <option value="{{ $clinic->id }}">
                                        {{ $clinic->getTranslation('name', app()->getLocale()) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Məkan --}}
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label text-white small fw-bold mb-1 ms-1">{{ __('home.location_label', ['default' => 'Məkan']) }}</label>
                        <button type="button" class="btn btn-light w-100 btn-lg d-flex align-items-center justify-content-between border-0 text-start text-muted fs-6" onclick="getLocation()">
                            <span><i class="fas fa-map-marker-alt me-2 text-danger"></i> {{ __('home.near_me', ['default' => 'Mənə yaxın']) }}</span>
                            <span class="badge bg-secondary text-white small-badge" id="distance-badge">{{ __('home.not_selected', ['default' => 'Yoxdur']) }}</span>
                        </button>
                        <input type="hidden" name="lat" id="lat">
                        <input type="hidden" name="lng" id="lng">
                    </div>

                    {{-- Axtar Düyməsi --}}
                    <div class="col-12 mt-4 text-center">
                        <button type="submit" class="btn btn-warning btn-lg rounded-pill px-5 fw-bold shadow-sm hover-scale text-dark">
                            <i class="fas fa-search me-2"></i> {{ __('home.search_btn', ['default' => 'Həkim Axtar']) }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Dalğa Effekti (SVG Separator) - Düzəliş edildi: bottom: -1px --}}
    <div class="position-absolute start-0 w-100 overflow-hidden" style="bottom: -1px; line-height: 0; z-index: 3;">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="width: 100%; height: 50px; fill: #f8f9fa; display: block;">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"></path>
        </svg>
    </div>
</section>

{{-- 2. HƏKİMLƏR SİYAHISI --}}
<div class="container pb-5 pt-4">
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-5">
        <div>
            <h6 class="text-primary fw-bold text-uppercase ls-1 mb-2">{{ __('home.professional_staff', ['default' => 'Peşəkar Heyət']) }}</h6>
            <h2 class="fw-bold m-0 text-dark display-6">{{ __('home.popular_doctors', ['default' => 'Məşhur Həkimlər']) }}</h2>
        </div>
        <a href="#" class="btn btn-outline-primary rounded-pill px-4 fw-bold mt-3 mt-md-0 group-hover-arrow">
            {{ __('home.view_all', ['default' => 'Hamısına bax']) }} <i class="fas fa-arrow-right ms-2 transition-transform"></i>
        </a>
    </div>

    <div class="row g-4">
        @forelse($doctors as $doctor)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 border-0 shadow-hover doctor-card rounded-4 overflow-hidden bg-white">

                    {{-- Favorit & Reytinq (Image Overlay) --}}
                    <div class="position-relative">
                        <div class="doctor-img-wrapper">
                            <img src="{{ $doctor->getFirstMediaUrl('avatar') ?: 'https://cdn-icons-png.flaticon.com/512/3774/3774299.png' }}"
                                 class="card-img-top doctor-img"
                                 alt="{{ $doctor->name }}">
                        </div>

                        <div class="position-absolute top-0 w-100 p-3 d-flex justify-content-between align-items-start z-2">
                            <span class="badge {{ $doctor->rating_avg > 0 ? 'bg-warning text-dark' : 'bg-light text-muted' }} fw-bold shadow-sm">
                                @if($doctor->rating_avg > 0)
                                    <i class="fas fa-star me-1"></i> {{ number_format($doctor->rating_avg, 1) }}
                                @else
                                    <i class="far fa-star me-1"></i> {{ __('home.new', ['default' => 'Yeni']) }}
                                @endif
                            </span>
                            <button class="btn btn-light btn-sm rounded-circle shadow-sm fav-btn">
                                <i class="far fa-heart text-danger"></i>
                            </button>
                        </div>

                        {{-- Overlay Gradient --}}
                        <div class="doctor-overlay"></div>
                    </div>

                    {{-- Məlumatlar --}}
                    <div class="card-body p-4 pt-3 position-relative">
                        <div class="text-center">
                            @if($doctor->specialty)
                                <span class="d-inline-block text-primary fw-bold small text-uppercase mb-2 tracking-wide">
                                    {{ $doctor->specialty->getTranslation('name', app()->getLocale()) }}
                                </span>
                            @endif

                            <h5 class="fw-bold text-dark mb-1 text-truncate">{{ $doctor->name }}</h5>

                            @if($doctor->clinic)
                                <div class="text-muted small mb-3">
                                    <i class="fas fa-hospital-alt me-1 text-secondary"></i>
                                    {{ $doctor->clinic->getTranslation('name', app()->getLocale()) }}
                                </div>
                            @endif
                        </div>

                        <hr class="opacity-10 my-3">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center text-muted small distance-info" data-lat="{{ $doctor->clinic->lat ?? 0 }}" data-lng="{{ $doctor->clinic->lng ?? 0 }}">
                                <div class="icon-circle bg-light text-danger me-2">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <span class="fw-bold text-dark me-1 dist-val">--</span> km
                            </div>
                            <div class="text-muted small">
                                <i class="fas fa-user-friends me-1 text-info"></i> {{ __('home.patient_count', ['default' => '120+ Pasiyent']) }}
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            {{-- Linkləri əlavə etdik: bookAppointment və doctorShow --}}
                            <a href="{{ route('doctor.show', $doctor->id) }}" class="btn btn-primary rounded-pill fw-bold py-2 shadow-sm btn-hover-effect">
                                {{ __('home.book_appointment', ['default' => 'Randevu Al']) }}
                            </a>
                            <a href="{{ route('doctor.show', $doctor->id) }}" class="btn btn-light rounded-pill btn-sm text-muted">
                                {{ __('home.view_profile', ['default' => 'Profilə Bax']) }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="empty-state">
                    <div class="icon-box bg-light text-muted rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-user-md fa-3x"></i>
                    </div>
                    <h4 class="text-muted fw-bold">{{ __('home.no_doctors_found', ['default' => 'Hələlik həkim yoxdur.']) }}</h4>
                    <p class="text-muted mb-0">{{ __('home.check_later_text', ['default' => 'Zəhmət olmasa daha sonra yoxlayın və ya axtarış parametrlərini dəyişin.']) }}</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if($doctors instanceof \Illuminate\Pagination\LengthAwarePaginator && $doctors->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $doctors->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

{{-- JS Scripts --}}
<script>
    function getLocation() {
        if (navigator.geolocation) {
            document.getElementById('distance-badge').innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            alert("{{ __('home.geo_not_supported', ['default' => 'Brauzeriniz Geolokasiyanı dəstəkləmir.']) }}");
        }
    }

    function showPosition(position) {
        let lat = position.coords.latitude;
        let lng = position.coords.longitude;
        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;
        let badge = document.getElementById('distance-badge');
        badge.innerText = "{{ __('home.location_found', ['default' => 'Tapıldı']) }}";
        badge.classList.remove('bg-secondary');
        badge.classList.add('bg-success');

        document.querySelectorAll('.distance-info').forEach(el => {
            let docLat = el.getAttribute('data-lat');
            let docLng = el.getAttribute('data-lng');
            if(docLat != 0 && docLng != 0) {
                let dist = calculateDistance(lat, lng, docLat, docLng);
                el.querySelector('.dist-val').innerText = dist.toFixed(1);
            }
        });
    }

    function showError(error) {
        document.getElementById('distance-badge').innerText = "{{ __('home.error_occurred', ['default' => 'Xəta']) }}";
    }

    function calculateDistance(lat1, lon1, lat2, lon2) {
        var R = 6371;
        var dLat = toRad(lat2-lat1);
        var dLon = toRad(lon2-lon1);
        var lat1 = toRad(lat1);
        var lat2 = toRad(lat2);
        var a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    function toRad(Value) { return Value * Math.PI / 180; }
</script>

{{-- Xüsusi CSS --}}
<style>
    /* Hero & Search */
    .search-box {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .animate-fade-in { animation: fadeIn 1s ease-out; }
    .animate-slide-up { animation: slideUp 0.8s ease-out; }

    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* Doctor Card Design */
    .doctor-card {
        transition: all 0.3s ease;
    }
    .shadow-hover:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .doctor-img-wrapper {
        height: 240px;
        overflow: hidden;
        background-color: #f8f9fa;
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }
    .doctor-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .doctor-card:hover .doctor-img {
        transform: scale(1.05);
    }
    .fav-btn:hover {
        background-color: #ffebeb;
        color: #dc3545 !important;
    }
    .icon-circle {
        width: 24px; height: 24px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 10px;
    }

    /* Button Effects */
    .hover-scale:hover { transform: scale(1.05); }
    .btn-hover-effect { transition: all 0.3s; }
    .btn-hover-effect:hover { box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3); }

    .group-hover-arrow:hover .fa-arrow-right {
        transform: translateX(5px);
    }
    .transition-transform { transition: transform 0.2s; }

    .tracking-tight { letter-spacing: -0.5px; }
    .tracking-wide { letter-spacing: 1px; }
    .ls-1 { letter-spacing: 1px; }
    .cursor-pointer { cursor: pointer; }
</style>

@endsection
