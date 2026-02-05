@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Ümumi Ayarlar</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.settings.general.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Təmir Rejimi -->
            <div class="col-md-6 mb-4">
                <div class="card shadow h-100 border-left-warning">
                    <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-tools me-2"></i> Təmir Rejimi</h6>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="maintenance_mode" id="maintenance_mode"
                                   value="1" {{ $setting->maintenance_mode ? 'checked' : '' }}>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Aktiv olduqda, sayt adi istifadəçilər üçün bağlanır. Yalnız Adminlər daxil ola bilər.</p>

                        <div class="mt-3">
                            <label class="form-label fw-bold">Ekranda görünəcək mesaj</label>

                            <!-- Dil Tabları -->
                            <ul class="nav nav-tabs mb-2" role="tablist">
                                @foreach($languages as $index => $lang)
                                    <li class="nav-item">
                                        <button class="nav-link {{ $index == 0 ? 'active' : '' }} py-1 px-3 small"
                                                data-bs-toggle="tab"
                                                data-bs-target="#maint-{{ $lang->code }}"
                                                type="button">
                                            {{ $lang->code }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content">
                                @foreach($languages as $index => $lang)
                                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="maint-{{ $lang->code }}">
                                        <textarea class="form-control" name="maintenance_text[{{ $lang->code }}]" rows="3"
                                                  placeholder="Saytımızda təmir işləri gedir...">{{ $setting->getTranslation('maintenance_text', $lang->code, false) }}</textarea>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Autentifikasiya və Giriş -->
            <div class="col-md-6 mb-4">
                <div class="card shadow h-100 border-left-primary">
                    <div class="card-header py-3 bg-white">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users-cog me-2"></i> Giriş və Qeydiyyat</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <strong class="d-block">Qeydiyyat (Registration)</strong>
                                <small class="text-muted">Yeni istifadəçi qeydiyyatını aç/bağla</small>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="enable_registration" value="1" {{ $setting->enable_registration ? 'checked' : '' }}>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <strong class="d-block">E-poçt Təsdiqi</strong>
                                <small class="text-muted">Qeydiyyatdan sonra email təsdiqi tələb olunsun?</small>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="enable_email_verification" value="1" {{ $setting->enable_email_verification ? 'checked' : '' }}>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="d-block">Sosial Giriş (Social Login)</strong>
                                <small class="text-muted">Google/Facebook ilə girişi ümumi idarə et</small>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="enable_social_login" value="1" {{ $setting->enable_social_login ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Təhlükəsizlik (2FA) -->
            <div class="col-md-12 mb-4">
                <div class="card shadow border-left-danger">
                    <div class="card-header py-3 bg-white">
                        <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-shield-alt me-2"></i> İki Faktorlu Təsdiqləmə (2FA)</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 border-end">
                                <div class="d-flex justify-content-between align-items-center p-3">
                                    <div>
                                        <strong class="d-block text-danger">Adminlər üçün 2FA</strong>
                                        <small class="text-muted">Admin panelə girişdə email/sms kodu tələb et</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="auth_2fa_admin" value="1" {{ $setting->auth_2fa_admin ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center p-3">
                                    <div>
                                        <strong class="d-block text-primary">İstifadəçilər üçün 2FA</strong>
                                        <small class="text-muted">Adi istifadəçilər (Həkim/Pasiyent) üçün girişdə kod tələb et</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="auth_2fa_user" value="1" {{ $setting->auth_2fa_user ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-5">
            <button type="submit" class="btn btn-primary btn-lg px-5">
                <i class="fas fa-save me-1"></i> Bütün Ayarları Yadda Saxla
            </button>
        </div>
    </form>
</div>
@endsection
