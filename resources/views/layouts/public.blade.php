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

        /* --- PC SIDEBAR DİZAYNI (Dəyişmədi) --- */
        @media (min-width: 992px) {
            .pc-sidebar {
                width: {{ $pc_sidebar->settings['width'] ?? '280px' }};
                height: 100vh;
                position: fixed; top: 0; left: 0;
                overflow-y: auto;
                background-color: {{ $pc_sidebar->settings['background_color'] ?? '#ffffff' }};
                color: {{ $pc_sidebar->settings['text_color'] ?? '#333333' }};
                border-right: 1px solid rgba(0,0,0,0.05);
                z-index: 1000;
                padding: 1.5rem;
                display: flex; flex-direction: column;
            }
            .main-content-wrapper {
                margin-left: {{ $pc_sidebar->settings['width'] ?? '280px' }};
                width: calc(100% - {{ $pc_sidebar->settings['width'] ?? '280px' }});
                min-height: 100vh;
            }
            /* PC-də mobil elementləri gizlət */
            .mobile-bottom-nav, .mobile-lang-sticky { display: none; }
        }

        /* --- MOBİL DİZAYN --- */
        @media (max-width: 991.98px) {
            .pc-sidebar { display: none; }
            .main-content-wrapper { width: 100%; margin-left: 0; padding-bottom: 90px; /* Navbar üçün yer */ }

            /* 1. Yapışqan Dil Düyməsi */
            .mobile-lang-sticky {
                position: fixed;
                top: 15px;
                right: 15px;
                z-index: 1040;
            }
            .lang-btn {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(5px);
                border: 1px solid #ddd;
                border-radius: 20px;
                padding: 5px 15px;
                font-size: 14px;
                font-weight: 600;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                color: #333;
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 5px;
            }

            /* 2. Aşağı Naviqasiya Paneli (Bottom Bar) */
            .mobile-bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background: #fff;
                height: 70px;
                display: flex;
                justify-content: space-between; /* Elementləri bərabər payla */
                align-items: center;
                padding: 0;
                box-shadow: 0 -2px 20px rgba(0,0,0,0.05);
                z-index: 1050;
                border-top-left-radius: 20px;
                border-top-right-radius: 20px;
            }

            .nav-item-mobile {
                flex: 1; /* Bütün elementlər eyni genişlikdə olsun */
                text-align: center;
                color: #999;
                font-size: 11px;
                text-decoration: none;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100%;
                padding-top: 5px;
            }
            .nav-item-mobile i { font-size: 20px; margin-bottom: 4px; }
            .nav-item-mobile.active { color: #0d6efd; font-weight: 600; }

            /* 3. Mərkəzi Axtarış Düyməsi (Floating) */
            .search-fab-container {
                position: relative;
                top: -25px; /* Paneldən yuxarı çıxarır */
                width: 70px; /* Sabit ölçü */
                height: 70px;
                flex-shrink: 0;
                display: flex;
                justify-content: center;
            }
            .search-fab {
                width: 60px;
                height: 60px;
                background: #0d6efd; /* Ana rəng */
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                font-size: 24px;
                box-shadow: 0 4px 15px rgba(13, 110, 253, 0.4);
                border: 5px solid #f8f9fa; /* Səhifənin fon rəngi ilə eyni çərçivə */
                cursor: pointer;
            }

            /* Boşluqları doldurmaq üçün placeholder (əgər menyu azdırsa) */
            .nav-placeholder { flex: 1; }
        }
    </style>
</head>
<body>

    {{-- === MOBİL ELEMENTLƏR === --}}

    {{-- 1. Yapışqan Dil Düyməsi (Header əvəzinə) --}}
    <div class="mobile-lang-sticky d-lg-none">
        <div class="dropdown">
            <a href="#" class="lang-btn dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-globe"></i> {{ strtoupper(app()->getLocale()) }}
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
    </div>

    {{-- 2. Aşağı Naviqasiya (Bottom Navbar) --}}
    <div class="mobile-bottom-nav d-lg-none">

        @php
            // Mobil menyuları ROLA GÖRƏ filtrələyirik
            $visible_mobile_menus = $mobile_menus->filter(function($menu) {
                // Hamı görə bilərsə
                if($menu->role == 'all') return true;

                // Yalnız Qonaqlar (Giriş etməyənlər)
                if($menu->role == 'guest' && !auth()->check()) return true;

                // Yalnız İstifadəçilər (User + Doctor)
                if($menu->role == 'auth_user' && auth()->check()) return true;

                // Yalnız Həkimlər
                if($menu->role == 'doctor' && auth()->check() && auth()->user()->hasRole('doctor')) return true;

                return false;
            });
        @endphp

        {{-- Sol Tərəf Menyular (Maksimum 2 ədəd) --}}
        @foreach($visible_mobile_menus->take(2) as $menu)
            <a href="{{ $menu->url }}" class="nav-item-mobile {{ request()->is(trim($menu->url, '/')) ? 'active' : '' }}">
                <i class="{{ $menu->icon ?? 'fas fa-circle' }}"></i>
                <span>{{ $menu->getTranslation('title', app()->getLocale()) }}</span>
            </a>
        @endforeach

        {{-- Əgər sol tərəfdə 2-dən az menyu varsa, boşluq burax ki, axtarış ortada qalsın --}}
        @if($visible_mobile_menus->take(2)->count() < 2)
             <div class="nav-placeholder"></div>
        @endif

        {{-- Mərkəzi Axtarış Düyməsi --}}
        <div class="search-fab-container">
            <div class="search-fab" data-bs-toggle="modal" data-bs-target="#searchModal">
                <i class="fas fa-search"></i>
            </div>
        </div>

        {{-- Sağ Tərəf Menyular (Növbəti 2 ədəd) --}}
        @foreach($visible_mobile_menus->skip(2)->take(2) as $menu)
            <a href="{{ $menu->url }}" class="nav-item-mobile {{ request()->is(trim($menu->url, '/')) ? 'active' : '' }}">
                <i class="{{ $menu->icon ?? 'fas fa-circle' }}"></i>
                <span>{{ $menu->getTranslation('title', app()->getLocale()) }}</span>
            </a>
        @endforeach

        {{-- Əgər sağ tərəfdə 2-dən az menyu varsa, boşluq burax --}}
        @if($visible_mobile_menus->skip(2)->take(2)->count() < 2)
             <div class="nav-placeholder"></div>
        @endif

    </div>

    {{-- Axtarış Modalı --}}
    <div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-body p-4">
                    <h5 class="mb-3 fw-bold">Axtarış</h5>
                    <form action="#" method="GET">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" class="form-control bg-light border-0" placeholder="Həkim, Xidmət və ya Məhsul axtar...">
                        </div>
                        <div class="d-grid mt-3">
                            <button class="btn btn-primary">Axtar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- === PC ELEMENTLƏR === --}}

    {{-- PC Sidebar --}}
    <aside class="pc-sidebar">
        <div class="mb-4 px-2">
            <a href="{{ route('home') }}" class="d-block text-decoration-none text-reset">
                @if($pc_sidebar->logo)
                    <img src="{{ asset($pc_sidebar->logo) }}" class="img-fluid" style="max-height: 50px;" alt="Logo">
                @else
                    <h3 class="fw-bold m-0">{{ $settings->site_name ?? 'AzDoktor' }}</h3>
                @endif
            </a>
        </div>

        <nav class="flex-grow-1">
            @if(isset($pc_menus))
                @foreach($pc_menus as $menu)
                    @include('partials.menu_item', ['menu' => $menu])
                @endforeach
            @endif
        </nav>

        <div class="mt-auto pt-3 border-top">
            @guest
                <div class="d-grid gap-2">
                    <a href="{{ route('login') }}" class="btn btn-primary">Giriş</a>
                </div>
            @else
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-reset" data-bs-toggle="dropdown">
                        <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 32px; height: 32px;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="text-truncate" style="max-width: 150px;">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu shadow">
                        <li><a class="dropdown-item" href="#">Profilim</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger">Çıxış</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endguest
        </div>
    </aside>

    {{-- Əsas Məzmun --}}
    <main class="main-content-wrapper">
        @yield('content')

        <footer class="bg-white text-center py-4 mt-auto border-top d-none d-lg-block">
            <div class="container">
                <small class="text-muted">
                    &copy; {{ date('Y') }} {{ $settings->site_name ?? 'AzDoktor' }}. Bütün hüquqlar qorunur.
                </small>
            </div>
        </footer>
    </main>

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
