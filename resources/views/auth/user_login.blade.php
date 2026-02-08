@extends('layouts.public')

@section('title', 'Giriş')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-sign-in-alt me-2"></i> Sistemə Giriş</h4>
                    <p class="mb-0 small opacity-75 mt-1">Həkim və Pasiyentlər üçün</p>
                </div>
                <div class="card-body p-4 p-md-5">

                    {{-- Uğurlu əməliyyat mesajı --}}
                    @if(session('success'))
                        <div class="alert alert-success small mb-3">
                            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger small mb-3">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">E-poçt Ünvanı</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                                <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="mail@numune.com">
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Şifrə</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                                <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="******">
                            </div>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small text-muted" for="remember">
                                    Məni xatırlas
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="text-decoration-none small text-primary fw-bold" href="{{ route('password.request') }}">
                                    Şifrəni unutmusunuz?
                                </a>
                            @endif
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                                Daxil Ol <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>

                        <div class="text-center border-top pt-4">
                            <p class="small text-muted mb-2">Hələ hesabınız yoxdur?</p>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
                                <i class="fas fa-user-plus me-1"></i> Qeydiyyatdan Keç
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        box-shadow: none;
        border-color: #0d6efd;
    }
    .input-group-text {
        background-color: #f8f9fa;
    }
</style>
@endsection
