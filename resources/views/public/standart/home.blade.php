@extends('layouts.public')

@section('title', $page->getTranslation('title', app()->getLocale()))

@section('content')

{{-- 1. HERO & AXTARIŞ HİSSƏSİ --}}
<section class="hero-section position-relative overflow-hidden" style="min-height: 90vh;">
    {{-- Animated Gradient Background --}}
    <div class="position-absolute top-0 start-0 w-100 h-100 gradient-bg">
        @php
            $bannerImage = $page->getMeta('banner_image') ? asset($page->getMeta('banner_image')) : 'https://img.freepik.com/free-photo/blur-hospital_1203-7957.jpg';
        @endphp
        <div class="position-absolute top-0 start-0 w-100 h-100"
             style="background: url('{{ $bannerImage }}') no-repeat center center/cover; opacity: 0.08;"></div>
    </div>

    {{-- Floating Elements --}}
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="container position-relative z-3 d-flex align-items-center" style="min-height: 90vh;">
        <div class="w-100 py-5">
            {{-- Hero Text --}}
            <div class="row justify-content-center text-center mb-5 hero-content">
                <div class="col-lg-8">
                    <div class="badge bg-white bg-opacity-20 text-white px-4 py-2 rounded-pill mb-4 backdrop-blur">
                        <i class="fas fa-sparkles me-2"></i>{{ __('home.professional_staff', ['default' => 'Peşəkar Heyət']) }}
                    </div>
                    <h1 class="display-3 fw-bold text-white mb-4 hero-title" style="line-height: 1.2;">
                        {{ $page->getTranslation('title', app()->getLocale()) }}
                    </h1>
                    <p class="lead text-white fs-5 mb-0 opacity-90 hero-subtitle">
                        {{ $page->getTranslation('content', app()->getLocale()) }}
                    </p>
                </div>
            </div>

            {{-- Modern Search Box --}}
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="modern-search-box">
                        <form action="#" method="GET">
                            <div class="row g-3">
                                {{-- Həkim Adı --}}
                                <div class="col-lg-3 col-md-6">
                                    <label class="search-label">
                                        <i class="fas fa-user-md label-icon"></i>
                                        {{ __('home.doctor_name_label', ['default' => 'Həkim Adı']) }}
                                    </label>
                                    <input type="text"
                                           class="search-input"
                                           placeholder="{{ __('home.search_doctor_placeholder', ['default' => 'Həkim adı axtar...']) }}">
                                </div>

                                {{-- İxtisas --}}
                                <div class="col-lg-3 col-md-6">
                                    <label class="search-label">
                                        <i class="fas fa-stethoscope label-icon"></i>
                                        {{ __('home.specialty_label', ['default' => 'İxtisas']) }}
                                    </label>
                                    <select class="search-input search-select">
                                        <option value="">{{ __('home.all_specialties', ['default' => 'Bütün İxtisaslar']) }}</option>
                                        @foreach($specialties as $specialty)
                                            <option value="{{ $specialty->id }}">
                                                {{ $specialty->getTranslation('name', app()->getLocale()) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Klinika --}}
                                <div class="col-lg-3 col-md-6">
                                    <label class="search-label">
                                        <i class="fas fa-hospital label-icon"></i>
                                        {{ __('home.clinic_label', ['default' => 'Klinika']) }}
                                    </label>
                                    <select class="search-input search-select">
                                        <option value="">{{ __('home.select_clinic', ['default' => 'Klinika Seçin']) }}</option>
                                        @foreach($clinics as $clinic)
                                            <option value="{{ $clinic->id }}">
                                                {{ $clinic->getTranslation('name', app()->getLocale()) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Məkan --}}
                                <div class="col-lg-3 col-md-6">
                                    <label class="search-label">
                                        <i class="fas fa-map-marker-alt label-icon"></i>
                                        {{ __('home.location_label', ['default' => 'Məkan']) }}
                                    </label>
                                    <button type="button"
                                            class="search-input location-btn text-start"
                                            onclick="getLocation()">
                                        <span class="location-text">{{ __('home.near_me', ['default' => 'Mənə yaxın']) }}</span>
                                        <span class="location-badge" id="distance-badge">{{ __('home.not_selected', ['default' => 'Yoxdur']) }}</span>
                                    </button>
                                    <input type="hidden" name="lat" id="lat">
                                    <input type="hidden" name="lng" id="lng">
                                </div>
                            </div>

                            {{-- Search Button --}}
                            <div class="text-center mt-4">
                                <button type="submit" class="btn-search-main">
                                    <span class="btn-search-text">{{ __('home.search_btn', ['default' => 'Həkim Axtar']) }}</span>
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modern Wave SVG --}}
    <div class="position-absolute start-0 w-100" style="bottom: -2px; z-index: 3;">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: auto; display: block;">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#F8F9FA"/>
        </svg>
    </div>
</section>

{{-- 2. HƏKİMLƏR SİYAHISI --}}
<div class="doctors-section py-5" style="background: #F8F9FA;">
    <div class="container py-4">
        {{-- Section Header --}}
        <div class="section-header mb-5">
            <div class="row align-items-end">
                <div class="col-md-8">
                    <div class="section-badge mb-3">
                        <i class="fas fa-star me-2"></i>{{ __('home.professional_staff', ['default' => 'Peşəkar Heyət']) }}
                    </div>
                    <h2 class="section-title">{{ __('home.popular_doctors', ['default' => 'Məşhur Həkimlər']) }}</h2>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="#" class="btn-view-all">
                        {{ __('home.view_all', ['default' => 'Hamısına bax']) }}
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Doctors Grid --}}
        <div class="row g-4">
            @forelse($doctors as $doctor)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="doctor-card-modern">
                        {{-- Image Container --}}
                        <div class="doctor-image-container">
                            <img src="{{ $doctor->getFirstMediaUrl('avatar') ?: 'https://cdn-icons-png.flaticon.com/512/3774/3774299.png' }}"
                                 class="doctor-image"
                                 alt="{{ $doctor->name }}">

                            {{-- Top Badges --}}
                            <div class="doctor-badges">
                                <div class="rating-badge">
                                    @if($doctor->rating_avg > 0)
                                        <i class="fas fa-star"></i>
                                        <span>{{ number_format($doctor->rating_avg, 1) }}</span>
                                    @else
                                        <i class="fas fa-certificate"></i>
                                        <span>{{ __('home.new', ['default' => 'Yeni']) }}</span>
                                    @endif
                                </div>
                                <button class="favorite-btn">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="doctor-card-body">
                            @if($doctor->specialty)
                                <div class="specialty-tag">
                                    {{ $doctor->specialty->getTranslation('name', app()->getLocale()) }}
                                </div>
                            @endif

                            <h3 class="doctor-name">{{ $doctor->name }}</h3>

                            @if($doctor->clinic)
                                <div class="clinic-info">
                                    <i class="fas fa-hospital"></i>
                                    <span>{{ $doctor->clinic->getTranslation('name', app()->getLocale()) }}</span>
                                </div>
                            @endif

                            <div class="doctor-meta">
                                <div class="meta-item distance-info"
                                     data-lat="{{ $doctor->clinic->lat ?? 0 }}"
                                     data-lng="{{ $doctor->clinic->lng ?? 0 }}">
                                    <i class="fas fa-location-dot"></i>
                                    <span><span class="dist-val">--</span> km</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-users"></i>
                                    <span>{{ __('home.patient_count', ['default' => '120+ Pasiyent']) }}</span>
                                </div>
                            </div>

                            <div class="doctor-actions">
                                <a href="{{ route('doctor.show', $doctor->id) }}" class="btn-appointment">
                                    {{ __('home.book_appointment', ['default' => 'Randevu Al']) }}
                                </a>
                                <a href="{{ route('doctor.show', $doctor->id) }}" class="btn-profile">
                                    {{ __('home.view_profile', ['default' => 'Profil']) }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state-modern">
                        <div class="empty-icon">
                            <i class="fas fa-user-doctor"></i>
                        </div>
                        <h3>{{ __('home.no_doctors_found', ['default' => 'Hələlik həkim yoxdur.']) }}</h3>
                        <p>{{ __('home.check_later_text', ['default' => 'Zəhmət olmasa daha sonra yoxlayın və ya axtarış parametrlərini dəyişin.']) }}</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($doctors instanceof \Illuminate\Pagination\LengthAwarePaginator && $doctors->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $doctors->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
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

{{-- Ultra Modern CSS --}}
<style>
    :root {
        --primary: #0066FF;
        --secondary: #6C757D;
        --success: #10B981;
        --warning: #F59E0B;
        --danger: #EF4444;
        --dark: #1F2937;
        --light: #F9FAFB;
        --radius-sm: 12px;
        --radius-md: 16px;
        --radius-lg: 24px;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.04);
        --shadow-md: 0 4px 16px rgba(0,0,0,0.08);
        --shadow-lg: 0 8px 32px rgba(0,0,0,0.12);
    }

    /* ============================================
       HERO SECTION
    ============================================ */
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        animation: gradientShift 15s ease infinite;
        background-size: 200% 200%;
    }

    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .floating-shapes .shape {
        position: absolute;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
        animation: float 20s infinite;
    }

    .shape-1 {
        width: 300px;
        height: 300px;
        top: 10%;
        left: 5%;
        animation-delay: 0s;
    }

    .shape-2 {
        width: 200px;
        height: 200px;
        top: 60%;
        right: 10%;
        animation-delay: 5s;
    }

    .shape-3 {
        width: 150px;
        height: 150px;
        bottom: 10%;
        left: 50%;
        animation-delay: 10s;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -30px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
    }

    .hero-content {
        animation: fadeInUp 1s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .backdrop-blur {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    /* ============================================
       MODERN SEARCH BOX
    ============================================ */
    .modern-search-box {
        background: white;
        border-radius: var(--radius-lg);
        padding: 40px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        animation: slideUp 0.8s ease-out 0.3s both;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .search-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .label-icon {
        margin-right: 8px;
        color: var(--primary);
        font-size: 14px;
    }

    .search-input {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #E5E7EB;
        border-radius: var(--radius-sm);
        font-size: 15px;
        color: var(--dark);
        background: white;
        transition: all 0.3s ease;
        outline: none;
    }

    .search-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(0,102,255,0.1);
    }

    .search-select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%236B7280' d='M4 6l4 4 4-4z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 40px;
    }

    .location-btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        text-align: left;
    }

    .location-text {
        font-size: 15px;
        color: var(--secondary);
    }

    .location-badge {
        padding: 4px 12px;
        background: #E5E7EB;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: var(--dark);
        transition: all 0.3s;
    }

    .location-badge.bg-success {
        background: var(--success) !important;
        color: white !important;
    }

    .btn-search-main {
        padding: 16px 48px;
        background: linear-gradient(135deg, var(--primary) 0%, #0052CC 100%);
        color: white;
        border: none;
        border-radius: 50px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 24px rgba(0,102,255,0.3);
        display: inline-flex;
        align-items: center;
    }

    .btn-search-main:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(0,102,255,0.4);
    }

    .btn-search-main:active {
        transform: translateY(0);
    }

    /* ============================================
       DOCTORS SECTION
    ============================================ */
    .section-header {
        position: relative;
    }

    .section-badge {
        display: inline-block;
        padding: 8px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .section-title {
        font-size: 42px;
        font-weight: 800;
        color: var(--dark);
        margin: 0;
        line-height: 1.2;
    }

    .btn-view-all {
        display: inline-flex;
        align-items: center;
        padding: 12px 28px;
        background: white;
        color: var(--primary);
        border: 2px solid var(--primary);
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-view-all:hover {
        background: var(--primary);
        color: white;
        transform: translateX(5px);
    }

    /* ============================================
       DOCTOR CARDS
    ============================================ */
    .doctor-card-modern {
        background: white;
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: var(--shadow-sm);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .doctor-card-modern:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
    }

    .doctor-image-container {
        position: relative;
        height: 280px;
        overflow: hidden;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .doctor-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .doctor-card-modern:hover .doctor-image {
        transform: scale(1.1);
    }

    .doctor-badges {
        position: absolute;
        top: 16px;
        left: 16px;
        right: 16px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        z-index: 2;
    }

    .rating-badge {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(10px);
        border-radius: 50px;
        font-weight: 700;
        font-size: 14px;
        color: var(--dark);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .rating-badge i {
        color: var(--warning);
        font-size: 12px;
    }

    .favorite-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(10px);
        border: none;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .favorite-btn i {
        color: var(--danger);
        font-size: 16px;
    }

    .favorite-btn:hover {
        background: var(--danger);
        transform: scale(1.1);
    }

    .favorite-btn:hover i {
        color: white;
    }

    .doctor-card-body {
        padding: 24px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .specialty-tag {
        display: inline-block;
        padding: 6px 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
        width: fit-content;
    }

    .doctor-name {
        font-size: 20px;
        font-weight: 700;
        color: var(--dark);
        margin: 0 0 8px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .clinic-info {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--secondary);
        font-size: 13px;
        margin-bottom: 16px;
    }

    .clinic-info i {
        color: var(--primary);
        font-size: 12px;
    }

    .doctor-meta {
        display: flex;
        gap: 20px;
        padding: 16px 0;
        margin-bottom: 16px;
        border-top: 1px solid #F3F4F6;
        border-bottom: 1px solid #F3F4F6;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: var(--secondary);
        font-weight: 600;
    }

    .meta-item i {
        color: var(--primary);
        font-size: 14px;
    }

    .doctor-actions {
        display: grid;
        gap: 10px;
        margin-top: auto;
    }

    .btn-appointment {
        padding: 12px 24px;
        background: linear-gradient(135deg, var(--primary) 0%, #0052CC 100%);
        color: white;
        border: none;
        border-radius: var(--radius-sm);
        font-weight: 700;
        font-size: 14px;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        display: block;
    }

    .btn-appointment:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,102,255,0.3);
        color: white;
    }

    .btn-profile {
        padding: 10px 24px;
        background: transparent;
        color: var(--dark);
        border: 2px solid #E5E7EB;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 13px;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        display: block;
    }

    .btn-profile:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: rgba(0,102,255,0.05);
    }

    /* ============================================
       EMPTY STATE
    ============================================ */
    .empty-state-modern {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 50%;
    }

    .empty-icon i {
        font-size: 48px;
        color: var(--secondary);
    }

    .empty-state-modern h3 {
        font-size: 24px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 12px;
    }

    .empty-state-modern p {
        font-size: 16px;
        color: var(--secondary);
        max-width: 500px;
        margin: 0 auto;
    }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 768px) {
        .modern-search-box {
            padding: 24px;
        }

        .section-title {
            font-size: 32px;
        }

        .hero-section {
            min-height: 100vh !important;
        }

        .btn-search-main {
            width: 100%;
            justify-content: center;
        }
    }
</style>

@endsection
