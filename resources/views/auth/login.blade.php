<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş - AzDoktor Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f6f9; display: flex; align-items: center; justify-content: center; height: 100vh; font-family: 'Segoe UI', sans-serif; }
        .login-card { width: 400px; border: none; box-shadow: 0 0 20px rgba(0,0,0,0.05); border-radius: 12px; overflow: hidden; }
        .login-header { background: #fff; padding: 30px 20px 10px; text-align: center; }
        .login-body { padding: 20px 30px 30px; background: #fff; }
        .form-control { padding: 10px 15px; border-radius: 6px; }
        .btn-primary { padding: 10px; font-weight: 600; }
        .social-btn { width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; border: 1px solid #ddd; color: #555; text-decoration: none; transition: 0.3s; margin: 0 5px; }
        .social-btn:hover { background: #f8f9fa; color: #000; border-color: #ccc; }
        .divider { display: flex; align-items: center; text-align: center; margin: 20px 0; color: #ccc; }
        .divider::before, .divider::after { content: ''; flex: 1; border-bottom: 1px solid #eee; }
        .divider:not(:empty)::before { margin-right: .5em; }
        .divider:not(:empty)::after { margin-left: .5em; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <div class="mb-3">
            <i class="fas fa-user-md fa-3x text-primary"></i>
        </div>
        <h4 class="fw-bold text-dark">AzDoktor</h4>
        <p class="text-muted small">İdarəetmə Panelinə Giriş</p>
    </div>

    <div class="login-body">
        @if(session('status'))
            <div class="alert alert-success small p-2 mb-3">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label small text-muted fw-bold">E-poçt</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                           placeholder="admin@azdoktor.com" value="{{ old('email') }}" required autofocus>
                </div>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <label class="form-label small text-muted fw-bold">Şifrə</label>
                    <a href="{{ route('password.request') }}" class="small text-decoration-none">Unutmusuz?</a>
                </div>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                           placeholder="******" required>
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label small text-muted" for="remember">Məni xatırlasın</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Daxil Ol</button>
        </form>

        <!-- Sosial Girişlər (Əgər aktivdirsə) -->
        @php
            $setting = \App\Models\GeneralSetting::first();
        @endphp

        @if($setting && $setting->enable_social_login)
            <div class="divider small">və ya</div>
            <div class="text-center">
                <a href="#" class="social-btn" title="Google ilə giriş"><i class="fab fa-google"></i></a>
                <a href="#" class="social-btn" title="Facebook ilə giriş"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-btn" title="Apple ilə giriş"><i class="fab fa-apple"></i></a>
            </div>
        @endif
    </div>
</div>

</body>
</html>
