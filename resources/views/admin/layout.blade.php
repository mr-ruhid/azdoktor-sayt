<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('menu.admin_panel_title') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; }

        /* Sidebar Dizaynı */
        .sidebar { min-height: 100vh; width: 280px; background-color: #343a40; color: #fff; position: fixed; overflow-y: auto; top: 0; bottom: 0; z-index: 1000; transition: all 0.3s; }
        .sidebar a { color: #c2c7d0; text-decoration: none; padding: 10px 15px; display: block; font-size: 15px; border-left: 3px solid transparent; }
        .sidebar a:hover, .sidebar a.active { background-color: #494e53; color: #fff; border-left-color: #007bff; }
        .sidebar .nav-header { font-size: 12px; text-transform: uppercase; color: #a1a6ab; padding: 15px 15px 5px; font-weight: bold; letter-spacing: 0.5px; margin-top: 10px; }
        .sidebar .nav-icon { width: 25px; text-align: center; margin-right: 8px; }

        /* Main Content Dizaynı */
        .main-content { margin-left: 280px; padding: 20px; transition: all 0.3s; }

        /* Dropdown Dizaynı */
        .dropdown-btn { cursor: pointer; display: flex; justify-content: space-between; align-items: center; }
        .dropdown-container { display: none; background-color: #3e444a; padding-left: 20px; }
        .dropdown-container a { font-size: 14px; padding: 8px 15px; }
        .active-drop { display: block; }
        .fa-chevron-down { font-size: 12px; transition: transform 0.3s; }
        .rotate-icon { transform: rotate(180deg); }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { margin-left: -280px; }
            .sidebar.active { margin-left: 0; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar d-flex flex-column p-3" id="sidebar">
    <h3 class="text-center py-3 border-bottom mb-0">AzDoktor <span class="badge bg-danger fs-6">v3</span></h3>

    <div class="nav flex-column">
        <!-- Başlanğıc -->
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt nav-icon"></i> {{ __('menu.dashboard') }}
        </a>

        <!-- Məzmun İdarəetməsi -->
        <div class="nav-header">{{ __('menu.content_management') }}</div>

        <a class="dropdown-btn" onclick="toggleMenu('pagesMenu', this)">
            <span><i class="fas fa-file-alt nav-icon"></i> {{ __('menu.pages') }}</span>
            <i class="fas fa-chevron-down"></i>
        </a>
        <div id="pagesMenu" class="dropdown-container {{ request()->routeIs('admin.pages.*') ? 'active-drop' : '' }}">
            <a href="{{ route('admin.pages.index') }}" class="{{ request()->routeIs('admin.pages.index') ? 'active' : '' }}">{{ __('menu.all_pages') }}</a>
            <a href="{{ route('admin.pages.create') }}" class="{{ request()->routeIs('admin.pages.create') ? 'active' : '' }}">{{ __('menu.new_page') }}</a>
        </div>

        <a href="{{ route('admin.menus.index') }}" class="{{ request()->routeIs('admin.menus.*') ? 'active' : '' }}"><i class="fas fa-bars nav-icon"></i> {{ __('menu.menus') }}</a>
        <a href="{{ route('admin.sidebars.index') }}" class="{{ request()->routeIs('admin.sidebars.*') ? 'active' : '' }}"><i class="fas fa-columns nav-icon"></i> {{ __('menu.sidebars') }}</a>

        <a class="dropdown-btn" onclick="toggleMenu('postsMenu', this)">
            <span><i class="fas fa-pen nav-icon"></i> {{ __('menu.posts') }}</span>
            <i class="fas fa-chevron-down"></i>
        </a>
        <div id="postsMenu" class="dropdown-container {{ request()->routeIs('admin.posts.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.tags.*') ? 'active-drop' : '' }}">
            <a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.index') ? 'active' : '' }}">{{ __('menu.all_posts') }}</a>
            <a href="{{ route('admin.posts.create') }}" class="{{ request()->routeIs('admin.posts.create') ? 'active' : '' }}">{{ __('menu.new_post') }}</a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">{{ __('menu.categories') }}</a>
            <a href="{{ route('admin.tags.index') }}" class="{{ request()->routeIs('admin.tags.index') ? 'active' : '' }}">{{ __('menu.tags') }}</a>
        </div>

        <a class="dropdown-btn" onclick="toggleMenu('commentsMenu', this)">
            <span><i class="fas fa-comments nav-icon"></i> {{ __('menu.comments') }}</span>
            <i class="fas fa-chevron-down"></i>
        </a>
        <div id="commentsMenu" class="dropdown-container {{ request()->routeIs('admin.comments.*') ? 'active-drop' : '' }}">
            <a href="{{ route('admin.comments.doctors') }}" class="{{ request()->routeIs('admin.comments.doctors') ? 'active' : '' }}">{{ __('menu.doctor_comments') }}</a>
            <a href="{{ route('admin.comments.blogs') }}" class="{{ request()->routeIs('admin.comments.blogs') ? 'active' : '' }}">{{ __('menu.blog_comments') }}</a>
            <a href="{{ route('admin.comments.products') }}" class="{{ request()->routeIs('admin.comments.products') ? 'active' : '' }}">{{ __('menu.product_comments') }}</a>
        </div>

        <a href="{{ route('admin.media.index') }}" class="{{ request()->routeIs('admin.media.*') ? 'active' : '' }}"><i class="fas fa-images nav-icon"></i> {{ __('menu.media') }}</a>
        <a href="{{ route('admin.plugins.index') }}" class="{{ request()->routeIs('admin.plugins.*') ? 'active' : '' }}"><i class="fas fa-plug nav-icon"></i> {{ __('menu.plugins') }}</a>

        <!-- Tibb Bölməsi -->
        <div class="nav-header">{{ __('menu.medical_section') }}</div>
        <a href="{{ route('admin.doctors.index') }}" class="{{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}"><i class="fas fa-user-md nav-icon"></i> {{ __('menu.doctors') }}</a>
        <!-- YENİ: Həkim Hesabları -->
        <a href="{{ route('admin.doctor_accounts.index') }}" class="{{ request()->routeIs('admin.doctor_accounts.*') ? 'active' : '' }}"><i class="fas fa-id-card nav-icon"></i> {{ __('menu.doctor_accounts') }}</a>
        <a href="{{ route('admin.clinics.index') }}" class="{{ request()->routeIs('admin.clinics.*') ? 'active' : '' }}"><i class="fas fa-hospital nav-icon"></i> {{ __('menu.clinics') }}</a>
        <!-- YENİ: İxtisaslar (Xidmətlər əvəzinə) -->
        <a href="{{ route('admin.specialties.index') }}" class="{{ request()->routeIs('admin.specialties.*') ? 'active' : '' }}"><i class="fas fa-briefcase-medical nav-icon"></i> {{ __('menu.specialties') }}</a>
        <a href="{{ route('admin.reservations.index') }}" class="{{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}"><i class="fas fa-calendar-check nav-icon"></i> {{ __('menu.reservations') }}</a>
        <a href="{{ route('admin.doctor_requests.index') }}" class="{{ request()->routeIs('admin.doctor_requests.*') ? 'active' : '' }}"><i class="fas fa-user-plus nav-icon"></i> {{ __('menu.doctor_requests') }}</a>

        <!-- E-Ticarət (Aptek) -->
        <div class="nav-header">{{ __('menu.ecommerce') }}</div>
        <!-- YENİ: Xidmətlər (Tibb bölməsindən bura gəldi) -->
        <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}"><i class="fas fa-stethoscope nav-icon"></i> {{ __('menu.services') }}</a>

        <a class="dropdown-btn" onclick="toggleMenu('productsMenu', this)">
            <span><i class="fas fa-pills nav-icon"></i> {{ __('menu.products') }}</span>
            <i class="fas fa-chevron-down"></i>
        </a>
        <div id="productsMenu" class="dropdown-container {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.product_categories.*') || request()->routeIs('admin.product_tags.*') ? 'active-drop' : '' }}">
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.index') ? 'active' : '' }}">{{ __('menu.product_list') }}</a>
            <a href="{{ route('admin.product_categories.index') }}" class="{{ request()->routeIs('admin.product_categories.index') ? 'active' : '' }}">{{ __('menu.categories') }}</a>
            <a href="{{ route('admin.product_tags.index') }}" class="{{ request()->routeIs('admin.product_tags.index') ? 'active' : '' }}">{{ __('menu.tags') }}</a>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"><i class="fas fa-shopping-cart nav-icon"></i> {{ __('menu.orders') }}</a>
        <a href="{{ route('admin.coupons.index') }}" class="{{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}"><i class="fas fa-tags nav-icon"></i> {{ __('menu.coupons') }}</a>

        <!-- İstifadəçilər & Əlaqə -->
        <div class="nav-header">{{ __('menu.users_contact') }}</div>
        <a class="dropdown-btn" onclick="toggleMenu('usersMenu', this)">
            <span><i class="fas fa-users nav-icon"></i> {{ __('menu.users') }}</span>
            <i class="fas fa-chevron-down"></i>
        </a>
        <div id="usersMenu" class="dropdown-container {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.subscribers.*') ? 'active-drop' : '' }}">
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">{{ __('menu.all_users') }}</a>
            <a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.index') ? 'active' : '' }}">{{ __('menu.roles_permissions') }}</a>
            <a href="{{ route('admin.subscribers.index') }}" class="{{ request()->routeIs('admin.subscribers.index') ? 'active' : '' }}">{{ __('menu.subscribers') }}</a>
        </div>
        <a href="{{ route('admin.contacts.index') }}" class="{{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}"><i class="fas fa-envelope nav-icon"></i> {{ __('menu.contact_messages') }}</a>

        <!-- Sistem & Tənzimləmələr -->
        <div class="nav-header">{{ __('menu.system_settings') }}</div>
        <a href="{{ route('admin.payments.index') }}" class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}"><i class="fas fa-history nav-icon"></i> {{ __('menu.payment_history') }}</a>

        <a class="dropdown-btn" onclick="toggleMenu('settingsMenu', this)">
            <span><i class="fas fa-cogs nav-icon"></i> {{ __('menu.settings') }}</span>
            <i class="fas fa-chevron-down"></i>
        </a>
        <div id="settingsMenu" class="dropdown-container {{ request()->routeIs('languages.*') || request()->routeIs('admin.settings.*') || request()->routeIs('admin.api.*') || request()->routeIs('admin.tools.*') || request()->routeIs('admin.logs.*') ? 'active-drop' : '' }}">
            <a href="{{ route('admin.settings.site') }}" class="{{ request()->routeIs('admin.settings.site') ? 'active' : '' }}">{{ __('menu.site_settings') }}</a>
            <a href="{{ route('admin.settings.general') }}" class="{{ request()->routeIs('admin.settings.general') ? 'active' : '' }}">{{ __('menu.general_settings') }}</a>

            <a href="{{ route('languages.index') }}" class="{{ request()->routeIs('languages.*') ? 'active' : '' }}">{{ __('menu.languages') }}</a>

            <!-- API İnteqrasiyaları -->
            <a href="#" class="text-warning"><i class="fas fa-code me-1"></i> {{ __('menu.api_integrations') }}</a>
            <a href="{{ route('admin.api.my') }}" class="{{ request()->routeIs('admin.api.my') ? 'active' : '' }}" style="padding-left: 35px;">{{ __('menu.my_apis') }}</a>
            <a href="{{ route('admin.api.shared') }}" class="{{ request()->routeIs('admin.api.shared') ? 'active' : '' }}" style="padding-left: 35px;">{{ __('menu.shared_apis') }}</a>

            <a href="{{ route('admin.settings.smtp') }}" class="{{ request()->routeIs('admin.settings.smtp') ? 'active' : '' }}">{{ __('menu.smtp') }}</a>
            <a href="{{ route('admin.tools.cache') }}" class="{{ request()->routeIs('admin.tools.cache') ? 'active' : '' }}">{{ __('menu.system_tools') }}</a>
            <a href="{{ route('admin.logs.index') }}" class="{{ request()->routeIs('admin.logs.index') ? 'active' : '' }}">{{ __('menu.logs') }}</a>
            <a href="{{ route('admin.tools.maintenance') }}" class="{{ request()->routeIs('admin.tools.maintenance') ? 'active' : '' }}">{{ __('menu.maintenance') }}</a>
        </div>

        <a class="dropdown-btn" onclick="toggleMenu('backupMenu', this)">
            <span><i class="fas fa-hdd nav-icon"></i> {{ __('menu.update_backup') }}</span>
            <i class="fas fa-chevron-down"></i>
        </a>
        <div id="backupMenu" class="dropdown-container {{ request()->routeIs('admin.system.*') ? 'active-drop' : '' }}">
            <a href="{{ route('admin.system.update') }}" class="{{ request()->routeIs('admin.system.update') ? 'active' : '' }}">{{ __('menu.update') }}</a>
            <a href="{{ route('admin.system.backups') }}" class="{{ request()->routeIs('admin.system.backups') ? 'active' : '' }}">{{ __('menu.backup') }}</a>
        </div>

        <div class="mb-5"></div>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4 rounded px-3">
        <div class="container-fluid">
            <!-- Mobile Toggle -->
            <button class="btn btn-outline-secondary d-md-none me-2" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>

            <span class="navbar-brand mb-0 h1 d-none d-md-block">{{ __('menu.admin_panel_title') }}</span>

            <div class="d-flex align-items-center ms-auto">

                <!-- Dinamik Dil Dəyişdirici -->
                <div class="dropdown me-3">
                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-globe me-1"></i> {{ LaravelLocalization::getCurrentLocaleNative() }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="languageDropdown">
                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-center"
                                   href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                    {{ $properties['native'] }}
                                    @if($localeCode == app()->getLocale())
                                        <i class="fas fa-check text-success small"></i>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- User Dropdown -->
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-dark text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="d-none d-sm-inline"><strong>Admin</strong></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser1">
                        <li><a class="dropdown-item" href="#">{{ __('menu.profile') }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#">{{ __('menu.logout') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Menyu açma/bağlama funksiyası (icon animasiyası ilə)
    function toggleMenu(id, element) {
        var menu = document.getElementById(id);
        var icon = element.querySelector('.fa-chevron-down');

        if (menu.style.display === "block") {
            menu.style.display = "none";
            if(icon) icon.classList.remove('rotate-icon');
        } else {
            menu.style.display = "block";
            if(icon) icon.classList.add('rotate-icon');
        }
    }

    // Mobil sidebar
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
    }
</script>
</body>
</html>
