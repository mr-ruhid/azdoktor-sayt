<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Dinamik SEO -->
    <title>{{ $page->seo_title ?? $settings->seo_title ?? $settings->site_name ?? 'AzDoktor' }}</title>
    <meta name="description" content="{{ $page->seo_description ?? $settings->seo_description ?? '' }}">
    <meta name="keywords" content="{{ $page->seo_keywords ?? $settings->seo_keywords ?? '' }}">

    <!-- Favicon -->
    @if(isset($settings->favicon))
        <link rel="icon" href="{{ asset($settings->favicon) }}">
    @endif

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; }

        /* PC Sidebar Dizaynı (Sənin istədiyin kimi) */
        .pc-sidebar {
            width: {{ $pc_sidebar->settings['width'] ?? '280px' }};
            background-color: {{ $pc_sidebar->settings['background_color'] ?? '#fff' }};
            color: {{ $pc_sidebar->settings['text_color'] ?? '#333' }};
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            border-right: 1px solid #eee;
            z-index: 1000;
        }

        .main-wrapper {
            margin-left: {{ $pc_sidebar->settings['width'] ?? '280px' }};
            padding: 20px;
        }

        /* Mobil Navbar (Yalnız mobildə görünür) */
        .mobile-navbar {
            display: none;
            background-color: {{ $mobile_navbar->settings['background_color'] ?? '#fff' }};
            color: {{ $mobile_navbar->settings['text_color'] ?? '#333' }};
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        @media (max-width: 991px) {
            .pc-sidebar { display: none; }
            .main-wrapper { margin-left: 0; padding: 10px; }
            .mobile-navbar { display: flex; justify-content: space-between; align-items: center; }
        }

        a { text-decoration: none; color: inherit; }
    </style>
</head>
<body>

    <!-- PC Sidebar -->
    <div class="pc-sidebar d-none d-lg-block p-4">
        <div class="text-center mb-5">
            @if(isset($settings->logo))
                <img src="{{ asset($settings->logo) }}" alt="Logo" style="max-height: 50px;">
            @else
                <h3>AzDoktor</h3>
            `@endif
        </div>

        <nav class="nav flex-column gap-2">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active fw-bold' : '' }}"><i class="fas fa-home me-2"></i> Ana Səhifə</a>
            <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active fw-bold' : '' }}"><i class="fas fa-info-circle me-2"></i> Haqqımızda</a>
            <a href="{{ route('clinics') }}" class="nav-link {{ request()->routeIs('clinics') ? 'active fw-bold' : '' }}"><i class="fas fa-hospital me-2"></i> Klinikalar</a>
            <a href="{{ route('shop') }}" class="nav-link {{ request()->routeIs('shop') ? 'active fw-bold' : '' }}"><i class="fas fa-pills me-2"></i> Mağaza</a>
            <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active fw-bold' : '' }}"><i class="fas fa-envelope me-2"></i> Əlaqə</a>
        </nav>

        <!-- Dil Dəyişdirici -->
        <div class="mt-5">
            <small class="text-muted">Dil:</small>
            <div class="btn-group w-100 mt-1">
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                       class="btn btn-sm btn-outline-secondary {{ app()->getLocale() == $localeCode ? 'active' : '' }}">
                       {{ strtoupper($localeCode) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Mobil Navbar -->
    @if(isset($mobile_navbar))
    <div class="mobile-navbar sticky-top">
        <div><i class="fas fa-bars fa-lg"></i></div> <!-- Mobil Menu Trigger -->

        @if(($mobile_navbar->settings['show_search'] ?? false))
        <div class="flex-grow-1 mx-3">
            <input type="text" class="form-control form-control-sm rounded-pill" placeholder="Axtarış...">
        </div>
        @endif

        <div>
             @if(isset($settings->logo))
                <img src="{{ asset($settings->logo) }}" alt="Logo" style="max-height: 30px;">
            @else
                <strong>AzDoc</strong>
            @endif
        </div>
    </div>
    @endif

    <!-- Əsas Məzmun -->
    <div class="main-wrapper">
        @yield('content')

        <footer class="mt-5 pt-4 border-top text-center text-muted small">
            &copy; {{ date('Y') }} {{ $settings->site_name ?? 'AzDoktor' }}. Bütün hüquqlar qorunur.
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
