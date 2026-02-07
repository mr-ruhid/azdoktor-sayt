@extends('layouts.public')

@section('title', $page->getTranslation('title', app()->getLocale()))

@section('content')

{{-- 1. HERO & AXTARIŞ HİSSƏSİ --}}
<section class="hero-section position-relative bg-primary text-white py-5 mb-5">
    {{-- Arxa Fon Dekorasiyası (Admin paneldən gələn şəkil) --}}
    @php
        $bannerImage = $page->getMeta('banner_image') ? asset($page->getMeta('banner_image')) : 'https://img.freepik.com/free-photo/blur-hospital_1203-7957.jpg';
    @endphp
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10"
         style="background-image: url('{{ $bannerImage }}'); background-size: cover; background-position: center;">
    </div>

    <div class="container position-relative z-1 py-4">
        <div class="row justify-content-center text-center mb-4">
            <div class="col-lg-8">
                <h1 class="fw-bold display-5 mb-3">{{ $page->getTranslation('title', app()->getLocale()) }}</h1>
                <p class="lead opacity-75">{{ $page->getTranslation('content', app()->getLocale()) }}</p>
            </div>
        </div>

        {{-- AXTARIŞ FORMU --}}
        <div class="card shadow-lg border-0 rounded-4 p-2 p-md-3">
            <form action="#" method="GET"> {{-- Gələcəkdə route('doctors.search') olacaq --}}
                <div class="row g-2 align-items-center">

                    {{-- Həkim Adı --}}
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" placeholder="{{ __('home.search_doctor_placeholder') }}">
                        </div>
                    </div>

                    {{-- İxtisas --}}
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-stethoscope text-muted"></i></span>
                            <select class="form-select border-start-0 ps-0">
                                <option value="">{{ __('home.all_specialties') }}</option>
                                @foreach($specialties as $specialty)
                                    <option value="{{ $specialty->id }}">
                                        {{ $specialty->getTranslation('name', app()->getLocale()) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Klinika --}}
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-hospital text-muted"></i></span>
                            <select class="form-select border-start-0 ps-0">
                                <option value="">{{ __('home.select_clinic') }}</option>
                                @foreach($clinics as $clinic)
                                    <option value="{{ $clinic->id }}">
                                        {{ $clinic->getTranslation('name', app()->getLocale()) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Location / Map Button --}}
                    <div class="col-md-3">
                        <button type="button" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-between" onclick="getLocation()">
                            <span><i class="fas fa-map-marker-alt me-2 text-danger"></i> {{ __('home.near_me') }}</span>
                            <span class="badge bg-light text-dark" id="distance-badge">{{ __('home.not_selected') }}</span>
                        </button>
                        <input type="hidden" name="lat" id="lat">
                        <input type="hidden" name="lng" id="lng">
                    </div>

                    {{-- Axtar Düyməsi --}}
                    <div class="col-12 mt-3 d-grid">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                            <i class="fas fa-search me-2"></i> {{ __('home.search_btn') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- 2. HƏKİMLƏR SİYAHISI --}}
<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0 text-dark">{{ __('home.popular_doctors') }}</h3>
        <a href="#" class="text-decoration-none fw-bold">{{ __('home.view_all') }} <i class="fas fa-arrow-right ms-1"></i></a>
    </div>

    <div class="row g-4">
        @forelse($doctors as $doctor)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 border-0 shadow-sm doctor-card rounded-4 overflow-hidden position-relative group">

                    {{-- Favorit Düyməsi --}}
                    <button class="btn btn-light btn-sm rounded-circle position-absolute top-0 end-0 m-3 shadow-sm z-2">
                        <i class="far fa-heart text-danger"></i>
                    </button>

                    {{-- Həkim Şəkli --}}
                    <div class="doctor-img-box bg-light d-flex justify-content-center align-items-end pt-3" style="height: 220px;">
                        <img src="{{ $doctor->getFirstMediaUrl('avatar') ?: 'https://cdn-icons-png.flaticon.com/512/3774/3774299.png' }}"
                             class="img-fluid"
                             style="max-height: 100%; object-fit: cover;"
                             alt="{{ $doctor->name }}">
                    </div>

                    {{-- Məlumatlar --}}
                    <div class="card-body p-3">
                        {{-- İxtisas --}}
                        @if($doctor->specialty)
                            <span class="badge bg-primary-subtle text-primary mb-2 rounded-pill px-3">
                                {{ $doctor->specialty->getTranslation('name', app()->getLocale()) }}
                            </span>
                        @endif

                        <h5 class="fw-bold mb-1 text-truncate">{{ $doctor->name }}</h5>

                        {{-- Klinika --}}
                        @if($doctor->clinic)
                            <div class="text-muted small mb-2 d-flex align-items-center">
                                <i class="fas fa-hospital-alt me-1 text-secondary"></i>
                                <span class="text-truncate">{{ $doctor->clinic->getTranslation('name', app()->getLocale()) }}</span>
                            </div>
                        @endif

                        {{-- Reytinq və Məsafə --}}
                        <div class="d-flex align-items-center justify-content-between mt-3 pb-3 border-bottom">
                            <div class="text-warning small">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <span class="text-dark fw-bold ms-1">4.8</span>
                                <span class="text-muted">(120)</span>
                            </div>
                            {{-- Məsafə (JS ilə hesablanacaq) --}}
                            <div class="text-muted small distance-info" data-lat="{{ $doctor->clinic->lat ?? 0 }}" data-lng="{{ $doctor->clinic->lng ?? 0 }}">
                                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                <span class="dist-val">--</span> km
                            </div>
                        </div>

                        {{-- Düymələr --}}
                        <div class="d-grid gap-2 mt-3">
                            <a href="#" class="btn btn-primary rounded-pill fw-bold">{{ __('home.book_appointment') }}</a>
                            <a href="#" class="btn btn-outline-secondary rounded-pill btn-sm">{{ __('home.view_profile') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-user-md fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">{{ __('home.no_doctors_found') }}</h4>
            </div>
        @endforelse
    </div>
</div>

{{-- Google Maps API (Placeholder) & JS Logic --}}
<script>
    // 1. İstifadəçinin yerini tapmaq
    function getLocation() {
        if (navigator.geolocation) {
            document.getElementById('distance-badge').innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            alert("{{ __('home.geo_not_supported') }}");
        }
    }

    // 2. Koordinatları almaq və Məsafəni hesablamaq
    function showPosition(position) {
        let lat = position.coords.latitude;
        let lng = position.coords.longitude;

        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;
        document.getElementById('distance-badge').innerText = "{{ __('home.location_found') }}";
        document.getElementById('distance-badge').classList.replace('bg-light', 'bg-success');
        document.getElementById('distance-badge').classList.replace('text-dark', 'text-white');

        // Bütün həkim kartlarındakı məsafəni hesabla
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
        document.getElementById('distance-badge').innerText = "{{ __('home.error_occurred') }}";
    }

    // 3. Haversine Formulu (İki nöqtə arası məsafə - km)
    function calculateDistance(lat1, lon1, lat2, lon2) {
        var R = 6371; // km
        var dLat = toRad(lat2-lat1);
        var dLon = toRad(lon2-lon1);
        var lat1 = toRad(lat1);
        var lat2 = toRad(lat2);

        var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        var d = R * c;
        return d;
    }

    function toRad(Value) {
        return Value * Math.PI / 180;
    }
</script>

<style>
    .doctor-card { transition: transform 0.3s, box-shadow 0.3s; }
    .doctor-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.1); }
</style>

@endsection
