@extends('layouts.public')

@section('title', $doctor->getTranslation('first_name', app()->getLocale()) . ' ' . $doctor->getTranslation('last_name', app()->getLocale()))

@section('content')

{{-- 1. HEADER PROFILE BÖLMƏSİ --}}
<section class="bg-white border-bottom pt-5 pb-4">
    <div class="container">
        <div class="row align-items-center">
            {{-- Həkim Şəkli --}}
            <div class="col-md-3 text-center text-md-start mb-4 mb-md-0">
                <div class="position-relative d-inline-block">
                    <img src="{{ $doctor->getFirstMediaUrl('avatar') ?: 'https://cdn-icons-png.flaticon.com/512/3774/3774299.png' }}"
                         class="img-fluid rounded-circle border border-4 border-light shadow-sm"
                         style="width: 180px; height: 180px; object-fit: cover;"
                         alt="{{ $doctor->first_name }}">
                    @if($doctor->rating_avg > 0)
                        <div class="position-absolute bottom-0 end-0 bg-warning text-dark px-2 py-1 rounded-pill small fw-bold shadow-sm border border-white">
                            <i class="fas fa-star"></i> {{ number_format($doctor->rating_avg, 1) }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Həkim Məlumatları --}}
            <div class="col-md-6 text-center text-md-start">
                @if($doctor->specialty)
                    <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-2 rounded-pill">
                        {{ $doctor->specialty->getTranslation('name', app()->getLocale()) }}
                    </span>
                @endif
                <h1 class="fw-bold mb-2">
                    Dr. {{ $doctor->getTranslation('first_name', app()->getLocale()) }} {{ $doctor->getTranslation('last_name', app()->getLocale()) }}
                </h1>

                @if($doctor->clinic)
                    <div class="text-muted mb-3 d-flex align-items-center justify-content-center justify-content-md-start">
                        <i class="fas fa-hospital text-secondary me-2"></i>
                        <span class="fw-medium">{{ $doctor->clinic->getTranslation('name', app()->getLocale()) }}</span>
                    </div>
                @endif

                {{-- Sosial Media --}}
                @if(!empty($doctor->social_links))
                    <div class="d-flex gap-2 justify-content-center justify-content-md-start mt-3">
                        @foreach(['facebook', 'instagram', 'twitter', 'linkedin', 'youtube', 'tiktok', 'website'] as $social)
                            @if(!empty($doctor->social_links[$social] ?? ($doctor->{'social_'.$social} ?? null)))
                                <a href="{{ $doctor->social_links[$social] ?? $doctor->{'social_'.$social} }}" target="_blank"
                                   class="btn btn-sm btn-light rounded-circle text-muted border social-icon-hover">
                                    <i class="fab fa-{{ $social == 'website' ? 'globe' : $social }}"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Randevu & Əlaqə Düymələri --}}
            <div class="col-md-3 text-center text-md-end mt-3 mt-md-0">
                @if($doctor->accepts_reservations)
                    <button class="btn btn-primary btn-lg rounded-pill w-100 mb-2 shadow-sm pulse-btn">
                        <i class="fas fa-calendar-check me-2"></i> Randevu Al
                    </button>
                @else
                    <button class="btn btn-secondary btn-lg rounded-pill w-100 mb-2" disabled>
                        <i class="fas fa-calendar-times me-2"></i> Qəbul Bağlıdır
                    </button>
                @endif

                @if($doctor->phone)
                    <a href="tel:{{ $doctor->phone }}" class="btn btn-outline-success rounded-pill w-100">
                        <i class="fas fa-phone me-2"></i> {{ $doctor->phone }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- 2. ƏSAS MƏZMUN (Tablar) --}}
<div class="container py-5">
    <div class="row g-4">

        {{-- SOL: Təfərrüatlar --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom p-0">
                    <ul class="nav nav-tabs nav-fill card-header-tabs m-0 border-0" id="doctorTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active py-3 fw-bold border-0 border-bottom border-primary border-3" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button">
                                <i class="far fa-user me-2"></i> Haqqında
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-3 fw-bold border-0 text-muted" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button">
                                <i class="far fa-clock me-2"></i> İş Qrafiki & Qiymət
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-3 fw-bold border-0 text-muted" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                                <i class="far fa-comment-dots me-2"></i> Rəylər ({{ $doctor->review_count ?? 0 }})
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content" id="doctorTabsContent">

                        {{-- Tab 1: Haqqında --}}
                        <div class="tab-pane fade show active" id="about" role="tabpanel">
                            <h5 class="fw-bold mb-3">Bioqrafiya</h5>
                            <div class="text-muted" style="line-height: 1.8;">
                                {!! nl2br(e($doctor->getTranslation('bio', app()->getLocale()))) !!}
                            </div>
                        </div>

                        {{-- Tab 2: İş Qrafiki & Qiymət --}}
                        <div class="tab-pane fade" id="info" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3 h-100">
                                        <h6 class="fw-bold text-primary mb-3"><i class="far fa-clock me-2"></i> İş Saatları</h6>
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2 d-flex justify-content-between">
                                                <span class="text-muted">İş Günləri:</span>
                                                <span class="fw-bold">{{ $doctor->work_days ?? $doctor->work_hours['days'] ?? 'Hər gün' }}</span>
                                            </li>
                                            <li class="mb-2 d-flex justify-content-between">
                                                <span class="text-muted">Saatlar:</span>
                                                <span class="fw-bold">
                                                    {{ $doctor->work_hour_start ?? '09:00' }} - {{ $doctor->work_hour_end ?? '18:00' }}
                                                </span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span class="text-muted">Növbə Tipi:</span>
                                                <span class="badge bg-info text-dark">
                                                    {{ ($doctor->queue_type == 1) ? 'Randevu (Saatlı)' : 'Canlı Növbə' }}
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3 h-100">
                                        <h6 class="fw-bold text-success mb-3"><i class="fas fa-tags me-2"></i> Qiymət & Qəbul</h6>
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2 d-flex justify-content-between">
                                                <span class="text-muted">Qəbul Qiyməti:</span>
                                                <span class="fw-bold text-success">{{ $doctor->price_range ?? 'Razılaşma ilə' }}</span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span class="text-muted">Qəbul Forması:</span>
                                                <span>{{ ($doctor->appointment_type == 1) ? 'Saytdan Qeydiyyat' : 'Əlaqə ilə' }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tab 3: Rəylər --}}
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            <div class="text-center py-5 text-muted">
                                <i class="far fa-comment-alt fa-3x mb-3 opacity-25"></i>
                                <p>Hələlik rəy yoxdur.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- SAĞ: Klinika Məlumatı --}}
        <div class="col-lg-4">
            @if($doctor->clinic)
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">İş Yeri</h5>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded p-2 me-3">
                                <i class="fas fa-hospital fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">{{ $doctor->clinic->getTranslation('name', app()->getLocale()) }}</h6>
                                <small class="text-muted">Klinika</small>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                            {{ $doctor->clinic->getTranslation('address', app()->getLocale()) }}
                        </p>

                        {{-- Kiçik Xəritə (Statik) --}}
                        <div class="ratio ratio-16x9 bg-light rounded overflow-hidden">
                            {{-- Gələcəkdə Google Maps Embed --}}
                            <div class="d-flex align-items-center justify-content-center text-muted">
                                <i class="fas fa-map text-muted opacity-25 fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Reklam və ya Digər Vidjetlər --}}
            <div class="card border-0 bg-primary text-white rounded-4 p-4 text-center">
                <i class="fas fa-headset fa-3x mb-3 opacity-50"></i>
                <h5>Kömək lazımdır?</h5>
                <p class="small opacity-75">Bizimlə əlaqə saxlayın, sizə uyğun həkimi tapmağa kömək edək.</p>
                <a href="{{ route('contact') }}" class="btn btn-light text-primary rounded-pill fw-bold w-100">Əlaqə</a>
            </div>
        </div>

    </div>
</div>

<style>
    .social-icon-hover:hover {
        background-color: #0d6efd;
        color: white !important;
        border-color: #0d6efd !important;
    }
    .pulse-btn {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(13, 110, 253, 0); }
        100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
    }

    /* Tab Active Style */
    .nav-tabs .nav-link { color: #6c757d; }
    .nav-tabs .nav-link.active { color: #0d6efd; background-color: transparent; }
    .nav-tabs .nav-link:hover { color: #0d6efd; }
</style>

@endsection
