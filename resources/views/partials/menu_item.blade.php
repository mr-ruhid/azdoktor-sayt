@php
    // Rol Yoxlanışı
    $showMenu = false;
    if($menu->role == 'all') $showMenu = true;
    elseif($menu->role == 'guest' && !auth()->check()) $showMenu = true;
    elseif($menu->role == 'auth_user' && auth()->check()) $showMenu = true;
    elseif($menu->role == 'doctor' && auth()->check() && auth()->user()->hasRole('doctor')) $showMenu = true;

    // URL təyini
    $url = $menu->url;
    if($url && !str_starts_with($url, 'http')) {
        $url = url($url == '/' ? '/' : '/'.$url);
    }

    // Alt menyusu varmı?
    $hasChildren = $menu->children->count() > 0;
@endphp

@if($showMenu)
    @if($hasChildren)
        <a href="#" class="nav-link-custom has-submenu">
            @if($menu->icon) <i class="{{ $menu->icon }}"></i> @endif
            <span>{{ $menu->getTranslation('title', app()->getLocale()) }}</span>
            <i class="fas fa-chevron-down ms-auto"></i>
        </a>
        <div class="submenu">
            @foreach($menu->children as $child)
                @include('partials.menu_item', ['menu' => $child])
            @endforeach
        </div>
    @else
        <a href="{{ $url ?? '#' }}" class="nav-link-custom {{ request()->fullUrl() == $url ? 'active' : '' }}">
            @if($menu->icon) <i class="{{ $menu->icon }}"></i> @endif
            <span>{{ $menu->getTranslation('title', app()->getLocale()) }}</span>
        </a>
    @endif
@endif
