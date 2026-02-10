@php
    // 1. Rol Yoxlanışı (Kimin görüb-görməyəcəyi)
    $showMenu = false;
    if($menu->role == 'all') $showMenu = true;
    elseif($menu->role == 'guest' && !auth()->check()) $showMenu = true;
    elseif($menu->role == 'auth_user' && auth()->check()) $showMenu = true;
    elseif($menu->role == 'doctor' && auth()->check() && auth()->user()->role_type == 2) $showMenu = true;

    // 2. URL və Lokalizasiya Tənzimləməsi
    $url = $menu->url;
    $href = '#';

    if ($url) {
        // Əgər xarici linkdirsə (http ilə başlayırsa) toxunmuruq
        if (str_starts_with($url, 'http')) {
            $href = $url;
        }
        // Daxili linkdirsə
        else {
            // Səhvən .blade yazılıbsa təmizləyirik
            $cleanUrl = str_replace(['.blade.php', '.blade'], '', $url);

            // "home" və ya "/" yazılıbsa düzgün root URL-i götürürük
            if ($cleanUrl == 'home' || $cleanUrl == '/') {
                $baseUrl = url('/');
            } else {
                $baseUrl = url($cleanUrl);
            }

            // Mcamara paketi ilə linki lokallaşdırırıq (məs: /shop -> /az/shop)
            $href = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL(app()->getLocale(), $baseUrl);
        }
    }

    // Alt menyusu varmı?
    $hasChildren = $menu->children->count() > 0;

    // 3. Alt menyu açıq qalmalıdır? (Aktivlik yoxlanışı)
    $isSubmenuOpen = false;
    if ($hasChildren) {
        foreach ($menu->children as $child) {
            // Uşaq menyunun linkini hazırlayırıq
            $cUrl = $child->url;
            $cHref = '#';
            if ($cUrl) {
                if (str_starts_with($cUrl, 'http')) {
                    $cHref = $cUrl;
                } else {
                    $cClean = str_replace(['.blade.php', '.blade'], '', $cUrl);
                    $cBase = ($cClean == 'home' || $cClean == '/') ? url('/') : url($cClean);
                    $cHref = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL(app()->getLocale(), $cBase);
                }
            }

            // Əgər cari səhifə uşaq menyunun linkidirsə, valideyn açıq qalsın
            if (request()->fullUrl() == $cHref) {
                $isSubmenuOpen = true;
                break;
            }
        }
    }
@endphp

@if($showMenu)
    @if($hasChildren)
        {{-- Dropdown Menyu --}}
        <div class="nav-item">
            {{-- Əgər alt menyu aktivdirsə 'open' klassı əlavə edilir (oxu çevirmək üçün) --}}
            <a href="#" class="nav-link-custom has-submenu d-flex justify-content-between align-items-center {{ $isSubmenuOpen ? 'open' : '' }}">
                <div>
                    @if($menu->icon) <i class="{{ $menu->icon }}"></i> @endif
                    <span>{{ $menu->getTranslation('title', app()->getLocale()) }}</span>
                </div>
                <i class="fas fa-chevron-down small"></i>
            </a>

            {{-- Default olaraq bağlı olsun (display: none), amma aktivdirsə açıq (block) --}}
            <div class="submenu ms-3 border-start ps-2" style="display: {{ $isSubmenuOpen ? 'block' : 'none' }};">
                @foreach($menu->children as $child)
                    @include('partials.menu_item', ['menu' => $child])
                @endforeach
            </div>
        </div>
    @else
        {{-- Tək Link --}}
        <a href="{{ $href }}" class="nav-link-custom {{ request()->fullUrl() == $href ? 'active' : '' }}">
            @if($menu->icon) <i class="{{ $menu->icon }}"></i> @endif
            <span>{{ $menu->getTranslation('title', app()->getLocale()) }}</span>
        </a>
    @endif
@endif
