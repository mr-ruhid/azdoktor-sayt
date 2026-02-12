<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ $settings->site_name ?? 'AzDoktor' }}</title>
    <meta name="description" content="@yield('description', $settings->meta_description ?? '')">

    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }

        /* --- PC SIDEBAR DİZAYNI --- */
        @media (min-width: 992px) {
            .pc-sidebar {
                width: {{ $pc_sidebar->settings['width'] ?? '280px' }};
                height: 100vh;
                position: fixed;
                top: 0;
                left: 0;
                overflow-y: auto;
                background-color: {{ $pc_sidebar->settings['background_color'] ?? '#ffffff' }};
                color: {{ $pc_sidebar->settings['text_color'] ?? '#333333' }};
                border-right: 1px solid rgba(0,0,0,0.05);
                z-index: 1000;
                padding: 1.5rem;
                display: flex;
                flex-direction: column;
                box-shadow: 2px 0 10px rgba(0,0,0,0.03);
            }

            .sidebar-logo { margin-bottom: 2rem; display: block; text-align: center; }
            .sidebar-logo img { max-height: 50px; width: auto; }

            .main-content-wrapper {
                margin-left: {{ $pc_sidebar->settings['width'] ?? '280px' }};
                width: calc(100% - {{ $pc_sidebar->settings['width'] ?? '280px' }});
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }
            .page-content { flex: 1; }
            .mobile-header, .mobile-bottom-nav { display: none; }
        }

        /* --- MOBİL DİZAYN --- */
        @media (max-width: 991.98px) {
            .pc-sidebar { display: none; }
            .main-content-wrapper { width: 100%; margin-left: 0; padding-bottom: 80px; display: flex; flex-direction: column; min-height: 100vh; }
            .page-content { flex: 1; margin-top: 60px; }

            .mobile-header {
                position: fixed; top: 0; left: 0; width: 100%; height: 60px;
                background: #fff; z-index: 1040; display: flex; align-items: center;
                justify-content: space-between; padding: 0 15px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            }

            .mobile-bottom-nav {
                position: fixed; bottom: 0; left: 0; width: 100%; height: 70px;
                background: #fff; z-index: 1050; display: flex;
                justify-content: space-around; align-items: center;
                border-top-left-radius: 20px; border-top-right-radius: 20px;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
            }
            .nav-item-mobile {
                text-align: center; color: #999; font-size: 11px; text-decoration: none;
                display: flex; flex-direction: column; align-items: center;
            }
            .nav-item-mobile i { font-size: 20px; margin-bottom: 3px; }
            .nav-item-mobile.active { color: #0d6efd; font-weight: 600; }

            .search-fab {
                width: 55px; height: 55px; background: #0d6efd; border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                color: #fff; font-size: 22px; box-shadow: 0 4px 10px rgba(13, 110, 253, 0.4);
                margin-top: -30px; border: 4px solid #f8f9fa;
            }
        }

        .nav-link-custom {
            color: inherit; padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 0.25rem;
            display: flex; align-items: center; text-decoration: none; transition: all 0.2s; font-weight: 500;
        }
        .nav-link-custom:hover, .nav-link-custom.active { background-color: rgba(0,0,0,0.05); color: #0d6efd; }
        .nav-link-custom i { width: 25px; text-align: center; margin-right: 10px; }

        .lang-btn {
            border: 1px solid #dee2e6; border-radius: 20px; padding: 5px 12px; font-size: 13px;
            color: #333; text-decoration: none; display: inline-flex; align-items: center; background: #fff;
        }
        .lang-btn:hover { background: #f1f1f1; }
    </style>
</head>
<body>

    {{-- === 1. MOBİL HEADER (Logo + Dil + Profil) === --}}
    <div class="mobile-header d-lg-none">
        <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none">
            @php
                $mobileLogo = $mobile_navbar->logo ? asset($mobile_navbar->logo) : ($pc_sidebar->logo ? asset($pc_sidebar->logo) : ($settings->logo ? asset($settings->logo) : null));
            @endphp

            @if($mobileLogo)
                <img src="{{ $mobileLogo }}" height="35" alt="Logo">
            @else
                <span class="fw-bold text-dark fs-5">{{ $settings->site_name ?? 'AzDoktor' }}</span>
            @endif
        </a>

        <div class="d-flex align-items-center gap-2">
            {{-- Dil Dəyişdirici --}}
            <div class="dropdown">
                <a href="#" class="lang-btn dropdown-toggle" data-bs-toggle="dropdown">
                    {{ strtoupper(app()->getLocale()) }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <li>
                            <a class="dropdown-item d-flex justify-content-between" rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                {{ $properties['native'] }}
                                @if(app()->getLocale() == $localeCode) <i class="fas fa-check text-success"></i> @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Mobil Profil Menyu --}}
            @auth
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none" data-bs-toggle="dropdown">
                        <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center fw-bold shadow-sm" style="width: 35px; height: 35px;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li class="px-3 py-2 border-bottom mb-2 bg-light rounded-top">
                            <span class="d-block fw-bold">{{ Auth::user()->name }}</span>
                            <small class="text-muted">{{ Auth::user()->role_type == 2 ? 'Həkim' : 'Pasiyent' }}</small>
                        </li>
                        @if(Auth::user()->role_type == 2)
                             <li><a class="dropdown-item" href="{{ route('doctor.dashboard') }}"><i class="fas fa-user-md me-2 text-primary"></i> {{ __('nav.dashboard', ['default' => 'Həkim Paneli']) }}</a></li>
                        @else
                             <li><a class="dropdown-item" href="{{ route('user.dashboard') }}"><i class="fas fa-cog me-2 text-secondary"></i> {{ __('nav.settings', ['default' => 'Tənzimləmələr']) }}</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> {{ __('nav.logout', ['default' => 'Çıxış']) }}</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-sm btn-primary rounded-pill px-3">Giriş</a>
            @endauth
        </div>
    </div>

    {{-- === 2. MOBİL BOTTOM NAV === --}}
    <div class="mobile-bottom-nav d-lg-none">
        @php
            $visible_mobile_menus = isset($mobile_menus) ? $mobile_menus->filter(function($menu) {
                if($menu->role == 'all') return true;
                if($menu->role == 'guest' && !auth()->check()) return true;
                if($menu->role == 'auth_user' && auth()->check()) return true;
                if($menu->role == 'doctor' && auth()->check() && auth()->user()->role_type == 2) return true;
                return false;
            }) : collect([]);
        @endphp

        @foreach($visible_mobile_menus->take(2) as $menu)
            <a href="{{ $menu->url }}" class="nav-item-mobile {{ request()->is(trim($menu->url, '/')) ? 'active' : '' }}">
                <i class="{{ $menu->icon ?? 'fas fa-circle' }}"></i>
                <span>{{ $menu->getTranslation('title', app()->getLocale()) }}</span>
            </a>
        @endforeach

        <div class="search-fab" data-bs-toggle="modal" data-bs-target="#searchModal">
            <i class="fas fa-search"></i>
        </div>

        @foreach($visible_mobile_menus->skip(2)->take(2) as $menu)
            <a href="{{ $menu->url }}" class="nav-item-mobile {{ request()->is(trim($menu->url, '/')) ? 'active' : '' }}">
                <i class="{{ $menu->icon ?? 'fas fa-circle' }}"></i>
                <span>{{ $menu->getTranslation('title', app()->getLocale()) }}</span>
            </a>
        @endforeach
    </div>

    {{-- === 3. PC SIDEBAR === --}}
    <aside class="pc-sidebar d-none d-lg-flex">
        <a href="{{ route('home') }}" class="sidebar-logo">
            @php
                $pcLogo = $pc_sidebar->logo ? asset($pc_sidebar->logo) : ($settings->logo ? asset($settings->logo) : null);
            @endphp
            @if($pcLogo) <img src="{{ $pcLogo }}" class="img-fluid" alt="Logo">
            @else <h4 class="fw-bold m-0 text-primary">{{ $settings->site_name ?? 'AzDoktor' }}</h4> @endif
        </a>

        <nav class="flex-grow-1 overflow-auto">
            @if(isset($pc_menus))
                @foreach($pc_menus as $menu)
                    @include('partials.menu_item', ['menu' => $menu])
                @endforeach
            @endif
        </nav>

        <div class="mt-auto pt-3 border-top">
            {{-- Dil --}}
            <div class="dropdown mb-3">
                <button class="btn btn-light w-100 d-flex align-items-center justify-content-between border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <span><i class="fas fa-globe me-2 text-muted"></i> {{ LaravelLocalization::getCurrentLocaleNative() }}</span>
                </button>
                <ul class="dropdown-menu w-100 shadow">
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <li>
                            <a class="dropdown-item d-flex justify-content-between" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                {{ $properties['native'] }}
                                @if(app()->getLocale() == $localeCode) <i class="fas fa-check text-success"></i> @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Profil --}}
            @guest
                <div class="d-grid gap-2">
                    <a href="{{ route('login') }}" class="btn btn-primary">{{ __('nav.login', ['default' => 'Giriş']) }}</a>
                </div>
            @else
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-reset p-2 rounded hover-bg-light" data-bs-toggle="dropdown">
                        <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <div class="fw-bold text-truncate" style="max-width: 140px;">{{ Auth::user()->name }}</div>
                            <small class="text-muted">{{ Auth::user()->role_type == 2 ? 'Həkim' : 'Pasiyent' }}</small>
                        </div>
                    </a>
                    <ul class="dropdown-menu w-100 shadow">
                        @if(Auth::user()->role_type == 2)
                             <li><a class="dropdown-item" href="{{ route('doctor.dashboard') }}">{{ __('nav.dashboard', ['default' => 'Həkim Paneli']) }}</a></li>
                        @else
                             <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">{{ __('nav.settings', ['default' => 'Tənzimləmələr']) }}</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger">{{ __('nav.logout', ['default' => 'Çıxış']) }}</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endguest
        </div>
    </aside>

    {{-- === 4. MAIN CONTENT === --}}
    <main class="main-content-wrapper">
        <div class="page-content">
            @yield('content')
        </div>

        <footer class="bg-white text-center py-4 border-top mt-auto d-none d-lg-block">
            <div class="container">
                <small class="text-muted">
                    &copy; {{ date('Y') }} {{ $settings->site_name ?? 'AzDoktor' }}. {{ __('nav.copyright', ['default' => 'Bütün hüquqlar qorunur.']) }}
                </small>
                @if(isset($settings->social_links))
                    <div class="mt-2">
                        @foreach($settings->social_links as $key => $link)
                            @if($link) <a href="{{ $link }}" class="text-muted me-2" target="_blank"><i class="fab fa-{{ $key }}"></i></a> @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </footer>
    </main>

    {{-- Axtarış Modalı --}}
    <div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-body p-4">
                    <h5 class="mb-3 fw-bold">{{ __('nav.search', ['default' => 'Axtarış']) }}</h5>
                    <form action="#" method="GET">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" class="form-control bg-light border-0" placeholder="{{ __('nav.search_placeholder', ['default' => 'Axtar...']) }}">
                        </div>
                        <div class="d-grid mt-3">
                            <button class="btn btn-primary">{{ __('nav.search_btn', ['default' => 'Axtar']) }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on('click', '.has-submenu', function(e) {
            e.preventDefault();
            $(this).toggleClass('open');
            $(this).next('.submenu').slideToggle(200);
        });
    </script>
</body>
</html>
