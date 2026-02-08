@extends('layouts.public')

@section('title', 'Qeydiyyat')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                {{-- TAB BAŞLIQLARI --}}
                <div class="card-header bg-white p-0 border-bottom">
                    <ul class="nav nav-tabs nav-fill" id="registerTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active py-3 fw-bold border-0 border-bottom border-primary border-3 rounded-0"
                                    id="patient-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#patient"
                                    type="button"
                                    role="tab">
                                <i class="fas fa-user me-2"></i> Pasiyent Qeydiyyatı
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 fw-bold border-0 rounded-0 text-muted"
                                    id="doctor-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#doctor"
                                    type="button"
                                    role="tab">
                                <i class="fas fa-user-md me-2"></i> Həkim Qeydiyyatı
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4 p-md-5">

                    {{-- Səhv Mesajları --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="tab-content" id="registerTabsContent">

                        {{-- 1. PASİYENT FORMU --}}
                        <div class="tab-pane fade show active" id="patient" role="tabpanel">
                            <div class="alert alert-light border text-center mb-4">
                                <small class="text-muted">Sifariş və Rezervasiyalarınızı idarə etmək üçün qeydiyyatdan keçin.</small>
                            </div>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Ad <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Soyad <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" value="{{ old('surname') }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">E-poçt <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Telefon</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="050 000 00 00">
                                        <small class="text-muted" style="font-size: 11px;">Rezervasiya üçün (Məcburi deyil)</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Doğum Tarixi <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror" name="birth_date" value="{{ old('birth_date') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Şifrə <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Şifrə Təsdiqi <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password_confirmation" required>
                                    </div>
                                </div>
                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold">Qeydiyyatı Tamamla</button>
                                </div>
                            </form>
                        </div>

                        {{-- 2. HƏKİM FORMU --}}
                        <div class="tab-pane fade" id="doctor" role="tabpanel">
                            <div class="alert alert-success bg-opacity-10 border-success text-center mb-4">
                                <small class="text-success fw-bold">Müraciətiniz Admin tərəfindən təsdiqləndikdən sonra hesabınız aktivləşəcək.</small>
                            </div>

                            <form method="POST" action="{{ route('register.doctor.submit') }}" enctype="multipart/form-data">
                                @csrf

                                <h6 class="text-success fw-bold border-bottom pb-2 mb-3">Şəxsi Məlumatlar</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-2">
                                        <label class="form-label">Titul <span class="text-danger">*</span></label>
                                        <select name="title" class="form-select" required>
                                            <option value="Dr.">Dr.</option>
                                            <option value="Uzm. Dr.">Uzm. Dr.</option>
                                            <option value="Prof. Dr.">Prof. Dr.</option>
                                            <option value="Doc. Dr.">Doc. Dr.</option>
                                            <option value="Phd. Dr.">Phd. Dr.</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Ad <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="first_name" required value="{{ old('first_name') }}">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Soyad <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="last_name" required value="{{ old('last_name') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">E-poçt <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" required value="{{ old('email') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Telefon <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="phone" required value="{{ old('phone') }}" placeholder="+994 50 000 00 00">
                                    </div>
                                </div>

                                <h6 class="text-success fw-bold border-bottom pb-2 mb-3">Peşəkar Məlumatlar</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">İxtisas <span class="text-danger">*</span></label>
                                        <select name="specialty_id" class="form-select" required>
                                            <option value="">Seçin...</option>
                                            @foreach($specialties as $specialty)
                                                <option value="{{ $specialty->id }}">{{ $specialty->getTranslation('name', app()->getLocale()) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Klinika / Filial</label>
                                        <select name="clinic_id" class="form-select">
                                            <option value="">(Əgər varsa) Seçin...</option>
                                            @foreach($clinics as $clinic)
                                                <option value="{{ $clinic->id }}">{{ $clinic->getTranslation('name', app()->getLocale()) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">CV Yüklə (PDF, DOC) <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="cv_file" required accept=".pdf,.doc,.docx">
                                        <div class="form-text">Maksimum 5MB</div>
                                    </div>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-success btn-lg rounded-pill fw-bold">Müraciəti Göndər</button>
                                </div>
                            </form>
                        </div>

                    </div>

                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="text-muted">Artıq hesabınız var? <a href="{{ route('login') }}" class="text-decoration-none fw-bold text-primary">Daxil olun</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Tab Aktiv olanda dizayn dəyişikliyi */
    .nav-tabs .nav-link { color: #6c757d; transition: all 0.3s; }
    .nav-tabs .nav-link.active { color: #0d6efd; background-color: transparent; }
    .nav-tabs .nav-link:hover { color: #0d6efd; background-color: rgba(13, 110, 253, 0.05); }

    /* Input Fokus Rəngi */
    .form-control:focus, .form-select:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15); }
</style>

{{-- SCRIPT: Tab Dəyişəndə Stil --}}
<script>
    document.addEventListener("DOMContentLoaded", function(){
        var triggerTabList = [].slice.call(document.querySelectorAll('#registerTabs button'))
        triggerTabList.forEach(function (triggerEl) {
            triggerEl.addEventListener('click', function (event) {
                // Tab aktiv olanda rəngləri dəyişmək üçün (əgər lazım olarsa)
                // Bootstrap özü active class-ı idarə edir
            })
        })
    });
</script>
@endsection
