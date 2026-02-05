<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İki Faktorlu Təsdiqləmə - AzDoktor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f6f9; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card { width: 400px; border: none; box-shadow: 0 0 20px rgba(0,0,0,0.1); border-radius: 10px; }
        .letter-spacing-2 { letter-spacing: 2px; }
    </style>
</head>
<body>

<div class="card p-4">
    <div class="text-center mb-4">
        <div class="mb-3 text-primary">
            <i class="fas fa-shield-alt fa-4x"></i>
        </div>
        <h4>Təhlükəsizlik Yoxlaması</h4>
        <p class="text-muted small">
            E-poçt ünvanınıza ({{ substr(auth()->user()->email, 0, 3) }}***{{ substr(auth()->user()->email, strpos(auth()->user()->email, '@')) }}) göndərilən 6 rəqəmli kodu daxil edin.
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success small text-center">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger small text-center">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.2fa.store') }}">
        @csrf
        <div class="mb-3">
            <input type="text" name="two_factor_code"
                   class="form-control form-control-lg text-center letter-spacing-2 @error('two_factor_code') is-invalid @enderror"
                   placeholder="123456" maxlength="6" autofocus required autocomplete="off"
                   oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">

            @error('two_factor_code')
                <div class="invalid-feedback text-center small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">Təsdiqlə</button>
    </form>

    <div class="text-center">
        <form method="POST" action="{{ route('admin.2fa.resend') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link text-decoration-none text-muted small p-0 border-0 bg-transparent">
                Kodu yenidən göndər
            </button>
        </form>
    </div>

    <hr>

    <div class="text-center mt-3">
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-danger small text-decoration-none fw-bold">
            <i class="fas fa-sign-out-alt me-1"></i> Çıxış
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
