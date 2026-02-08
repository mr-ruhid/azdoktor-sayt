@extends('layouts.public')

@section('title', $doctor->getTranslation('first_name', app()->getLocale()) . ' ' . $doctor->getTranslation('last_name', app()->getLocale()))

@section('content')

{{-- Success Message --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show container mt-3">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

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
                    <button class="btn btn-primary btn-lg rounded-pill w-100 mb-2 shadow-sm pulse-btn" data-bs-toggle="modal" data-bs-target="#appointmentModal">
                        <i class="fas fa-calendar-check me-2"></i> {{ __('doctor.book_appointment', ['default' => 'Randevu Al']) }}
                    </button>
                @else
                    <button class="btn btn-secondary btn-lg rounded-pill w-100 mb-2" disabled>
                        <i class="fas fa-calendar-times me-2"></i> {{ __('doctor.admission_closed', ['default' => 'Qəbul Bağlıdır']) }}
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
                                <i class="far fa-user me-2"></i> {{ __('doctor.tab_about', ['default' => 'Haqqında']) }}
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-3 fw-bold border-0 text-muted" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button">
                                <i class="far fa-clock me-2"></i> {{ __('doctor.tab_info', ['default' => 'İş Qrafiki & Qiymət']) }}
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-3 fw-bold border-0 text-muted" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                                <i class="far fa-comment-dots me-2"></i> {{ __('doctor.tab_reviews', ['default' => 'Rəylər']) }} ({{ $doctor->comments->count() }})
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content" id="doctorTabsContent">

                        {{-- Tab 1: Haqqında --}}
                        <div class="tab-pane fade show active" id="about" role="tabpanel">
                            <h5 class="fw-bold mb-3">{{ __('doctor.biography', ['default' => 'Bioqrafiya']) }}</h5>
                            <div class="text-muted" style="line-height: 1.8;">
                                {!! nl2br(e($doctor->getTranslation('bio', app()->getLocale()))) !!}
                            </div>
                        </div>

                        {{-- Tab 2: İş Qrafiki & Qiymət --}}
                        <div class="tab-pane fade" id="info" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3 h-100">
                                        <h6 class="fw-bold text-primary mb-3"><i class="far fa-clock me-2"></i> {{ __('doctor.work_hours', ['default' => 'İş Saatları']) }}</h6>
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2 d-flex justify-content-between">
                                                <span class="text-muted">{{ __('doctor.work_days', ['default' => 'İş Günləri:']) }}</span>
                                                <span class="fw-bold">{{ $doctor->work_days ?? $doctor->work_hours['days'] ?? __('doctor.every_day', ['default' => 'Hər gün']) }}</span>
                                            </li>
                                            <li class="mb-2 d-flex justify-content-between">
                                                <span class="text-muted">{{ __('doctor.hours', ['default' => 'Saatlar:']) }}</span>
                                                <span class="fw-bold">
                                                    {{ $doctor->work_hour_start ?? '09:00' }} - {{ $doctor->work_hour_end ?? '18:00' }}
                                                </span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span class="text-muted">{{ __('doctor.queue_type', ['default' => 'Növbə Tipi:']) }}</span>
                                                <span class="badge bg-info text-dark">
                                                    {{ ($doctor->queue_type == 1) ? __('doctor.queue_appointment', ['default' => 'Randevu (Saatlı)']) : __('doctor.queue_live', ['default' => 'Canlı Növbə']) }}
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3 h-100">
                                        <h6 class="fw-bold text-success mb-3"><i class="fas fa-tags me-2"></i> {{ __('doctor.price_admission', ['default' => 'Qiymət & Qəbul']) }}</h6>
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2 d-flex justify-content-between">
                                                <span class="text-muted">{{ __('doctor.admission_price', ['default' => 'Qəbul Qiyməti:']) }}</span>
                                                <span class="fw-bold text-success">{{ $doctor->price_range ?? __('doctor.negotiable', ['default' => 'Razılaşma ilə']) }}</span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span class="text-muted">{{ __('doctor.admission_type', ['default' => 'Qəbul Forması:']) }}</span>
                                                <span>{{ ($doctor->appointment_type == 1) ? __('doctor.registration_site', ['default' => 'Saytdan Qeydiyyat']) : __('doctor.registration_contact', ['default' => 'Əlaqə ilə']) }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tab 3: Rəylər --}}
                        <div class="tab-pane fade" id="reviews" role="tabpanel">

                            {{-- Şərh Formu --}}
                            <div class="bg-light p-4 rounded-3 mb-5 border">
                                <h6 class="fw-bold mb-3"><i class="far fa-edit me-2"></i> {{ __('doctor.write_review', ['default' => 'Rəy Yazın']) }}</h6>
                                <form action="{{ route('comment.submit') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="commentable_id" value="{{ $doctor->id }}">
                                    <input type="hidden" name="commentable_type" value="App\Models\Doctor">

                                    <div class="row g-2 mb-3">
                                        <div class="col-md-6">
                                            <input type="text" name="name" class="form-control" placeholder="{{ __('doctor.your_name', ['default' => 'Adınız']) }}" required value="{{ Auth::check() ? Auth::user()->name : '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="email" name="email" class="form-control" placeholder="{{ __('doctor.email', ['default' => 'E-poçt']) }}" required value="{{ Auth::check() ? Auth::user()->email : '' }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small text-muted me-2">{{ __('doctor.rating_label', ['default' => 'Qiymətləndirmə:']) }}</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rating" value="5" id="r5" checked>
                                            <label class="form-check-label text-warning" for="r5"><i class="fas fa-star"></i> 5</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rating" value="4" id="r4">
                                            <label class="form-check-label text-warning" for="r4"><i class="fas fa-star"></i> 4</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rating" value="3" id="r3">
                                            <label class="form-check-label text-warning" for="r3"><i class="fas fa-star"></i> 3</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rating" value="2" id="r2">
                                            <label class="form-check-label text-warning" for="r2"><i class="fas fa-star"></i> 2</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rating" value="1" id="r1">
                                            <label class="form-check-label text-warning" for="r1"><i class="fas fa-star"></i> 1</label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <textarea name="content" class="form-control" rows="3" placeholder="{{ __('doctor.review_placeholder', ['default' => 'Rəyiniz...']) }}" required></textarea>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">{{ __('doctor.submit_review', ['default' => 'Rəyi Göndər']) }}</button>
                                    </div>
                                </form>
                            </div>

                            {{-- Mövcud Şərhlər --}}
                            @forelse($doctor->comments as $comment)
                                <div class="d-flex mb-4 border-bottom pb-3">
                                    <div class="flex-shrink-0">
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 45px; height: 45px;">
                                            {{ substr($comment->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-bold mb-0">{{ $comment->name }}</h6>
                                            <small class="text-muted">{{ $comment->created_at->format('d.m.Y') }}</small>
                                        </div>
                                        <div class="text-warning small mb-2">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="{{ $i <= $comment->rating ? 'fas' : 'far' }} fa-star"></i>
                                            @endfor
                                        </div>
                                        <p class="text-dark mb-1">{{ $comment->content }}</p>

                                        @if($comment->replies->count() > 0)
                                            <div class="mt-3 ps-3 border-start border-3 border-primary bg-light p-2 rounded">
                                                <small class="fw-bold text-primary mb-1 d-block">Dr. {{ $doctor->last_name }}</small>
                                                <p class="small text-muted mb-0">{{ $comment->replies->first()->content }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted">
                                    <i class="far fa-comment-alt fa-3x mb-3 opacity-25"></i>
                                    <p>{{ __('doctor.no_reviews', ['default' => 'Hələlik rəy yoxdur.']) }}</p>
                                </div>
                            @endforelse

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
                        <h5 class="fw-bold mb-3">{{ __('doctor.workplace', ['default' => 'İş Yeri']) }}</h5>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded p-2 me-3">
                                <i class="fas fa-hospital fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">{{ $doctor->clinic->getTranslation('name', app()->getLocale()) }}</h6>
                                <small class="text-muted">{{ __('doctor.clinic', ['default' => 'Klinika']) }}</small>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                            {{ $doctor->clinic->getTranslation('address', app()->getLocale()) }}
                        </p>

                        {{-- Kiçik Xəritə (Statik) --}}
                        <div class="ratio ratio-16x9 bg-light rounded overflow-hidden">
                            <div class="d-flex align-items-center justify-content-center text-muted w-100 h-100">
                                <i class="fas fa-map text-muted opacity-25 fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Reklam və ya Digər Vidjetlər --}}
            <div class="card border-0 bg-primary text-white rounded-4 p-4 text-center">
                <i class="fas fa-headset fa-3x mb-3 opacity-50"></i>
                <h5>{{ __('doctor.need_help', ['default' => 'Kömək lazımdır?']) }}</h5>
                <p class="small opacity-75">{{ __('doctor.help_text', ['default' => 'Bizimlə əlaqə saxlayın, sizə uyğun həkimi tapmağa kömək edək.']) }}</p>
                <a href="{{ route('contact') }}" class="btn btn-light text-primary rounded-pill fw-bold w-100">{{ __('doctor.contact_btn', ['default' => 'Əlaqə']) }}</a>
            </div>
        </div>

    </div>
</div>

{{-- RANDEVU MODALI --}}
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">{{ __('doctor.book_appointment', ['default' => 'Randevu Al']) }} - Dr. {{ $doctor->getTranslation('first_name', app()->getLocale()) }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                {{-- Addım 1: Tarix və Saat --}}
                <div id="step-1">
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('doctor.select_date', ['default' => 'Tarix Seçin:']) }}</label>
                        <input type="date" class="form-control form-control-lg" id="res-date" min="{{ date('Y-m-d') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('doctor.select_time', ['default' => 'Saat Seçin:']) }}</label>
                        <div id="slots-container" class="d-flex flex-wrap gap-2 justify-content-center py-3 bg-light rounded">
                            <span class="text-muted small">{{ __('doctor.please_select_date', ['default' => 'Zəhmət olmasa tarix seçin...']) }}</span>
                        </div>
                        <input type="hidden" id="selected-time">
                    </div>

                    <div class="d-grid mt-4">
                        <button class="btn btn-primary rounded-pill" id="btn-next" disabled>{{ __('doctor.next_step', ['default' => 'Növbəti Addım']) }} <i class="fas fa-arrow-right ms-2"></i></button>
                    </div>
                </div>

                {{-- Addım 2: Əlaqə Məlumatları --}}
                <div id="step-2" style="display: none;">
                    <div class="alert alert-info d-flex align-items-center mb-4">
                        <i class="fas fa-clock me-2 fs-4"></i>
                        <div>
                            {{ __('doctor.selected', ['default' => 'Seçildi:']) }} <strong id="summary-date"></strong> {{ __('doctor.at_time', ['default' => 'saat']) }} <strong id="summary-time"></strong>
                        </div>
                    </div>

                    <form id="reservation-form">
                        @csrf
                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                        <input type="hidden" name="reservation_date" id="form-date">
                        <input type="hidden" name="time" id="form-time">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('doctor.your_name', ['default' => 'Adınız, Soyadınız']) }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required value="{{ Auth::check() ? Auth::user()->name : '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('doctor.phone', ['default' => 'Telefon']) }} <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" required placeholder="050 000 00 00">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">{{ __('doctor.email_optional', ['default' => 'E-poçt (İstəyə bağlı)']) }}</label>
                                <input type="email" name="email" class="form-control" value="{{ Auth::check() ? Auth::user()->email : '' }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">{{ __('doctor.note', ['default' => 'Qeydiniz']) }}</label>
                                <textarea name="note" class="form-control" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-light rounded-pill" id="btn-back">{{ __('doctor.back', ['default' => 'Geri']) }}</button>
                            <button type="submit" class="btn btn-success rounded-pill px-4">{{ __('doctor.confirm', ['default' => 'Təsdiqlə']) }} <i class="fas fa-check ms-2"></i></button>
                        </div>
                    </form>
                </div>
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

    /* Time Slot Style */
    .time-slot {
        width: 80px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .time-slot.selected {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
    .time-slot.disabled {
        background-color: #e9ecef;
        color: #adb5bd;
        cursor: not-allowed;
        text-decoration: line-through;
    }

    /* Tab Active Style */
    .nav-tabs .nav-link { color: #6c757d; }
    .nav-tabs .nav-link.active { color: #0d6efd; background-color: transparent; }
    .nav-tabs .nav-link:hover { color: #0d6efd; }
</style>

{{-- SCRIPT: AJAX & Modal Logic --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Tarix dəyişəndə saatları gətir
        $('#res-date').on('change', function() {
            let date = $(this).val();
            let doctorId = {{ $doctor->id }};
            let container = $('#slots-container');

            container.html('<div class="spinner-border text-primary" role="status"></div>');
            $('#btn-next').prop('disabled', true);

            $.ajax({
                url: '{{ route('doctor.slots') }}',
                type: 'GET',
                data: { date: date, doctor_id: doctorId },
                success: function(res) {
                    container.empty();
                    if(res.slots.length === 0) {
                        container.html('<span class="text-muted">{{ __('doctor.no_slots', ['default' => 'Bu tarix üçün boş vaxt yoxdur.']) }}</span>');
                    } else {
                        res.slots.forEach(slot => {
                            let btnClass = slot.available ? 'btn-outline-primary' : 'btn-outline-secondary disabled';
                            let disabled = slot.available ? '' : 'disabled';

                            container.append(`
                                <button type="button" class="btn ${btnClass} time-slot" ${disabled} data-time="${slot.time}">
                                    ${slot.time}
                                </button>
                            `);
                        });
                    }
                },
                error: function() {
                    container.html('<span class="text-danger">{{ __('doctor.error_occurred', ['default' => 'Xəta baş verdi. Yenidən cəhd edin.']) }}</span>');
                }
            });
        });

        // Saat seçimi
        $(document).on('click', '.time-slot:not(.disabled)', function() {
            $('.time-slot').removeClass('selected active');
            $(this).addClass('selected active');
            $('#selected-time').val($(this).data('time'));
            $('#btn-next').prop('disabled', false);
        });

        // Addım keçidləri
        $('#btn-next').click(function() {
            let date = $('#res-date').val();
            let time = $('#selected-time').val();

            $('#summary-date').text(date);
            $('#summary-time').text(time);
            $('#form-date').val(date);
            $('#form-time').val(time);

            $('#step-1').fadeOut(200, function() {
                $('#step-2').fadeIn(200);
            });
        });

        $('#btn-back').click(function() {
            $('#step-2').fadeOut(200, function() {
                $('#step-1').fadeIn(200);
            });
        });

        // Form Submit
        $('#reservation-form').on('submit', function(e) {
            e.preventDefault();
            let btn = $(this).find('button[type="submit"]');
            let originalText = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Göndərilir...');

            $.ajax({
                url: '{{ route('doctor.book') }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    alert(res.message);
                    location.reload();
                },
                error: function(err) {
                    btn.prop('disabled', false).html(originalText);
                    alert(err.responseJSON.message || 'Xəta baş verdi');
                }
            });
        });
    });
</script>

@endsection
