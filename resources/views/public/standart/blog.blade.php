@extends('layouts.public')

@section('title', $page->getTranslation('title', app()->getLocale()))

@section('content')

{{-- Hero Section --}}
<div class="bg-primary bg-opacity-10 py-5">
    <div class="container text-center">
        <h1 class="fw-bold display-5 mb-2 text-dark">{{ $page->getTranslation('title', app()->getLocale()) }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">{{ __('blog.home', ['default' => 'Ana Səhifə']) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('blog.blog', ['default' => 'Bloq']) }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">

        {{-- SOL TƏRƏF: Məqalələr --}}
        <div class="col-lg-8">
            <div class="row g-4">
                @forelse($posts as $post)
                    <div class="col-md-6">
                         <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-card">
                             {{-- Şəkil --}}
                             <a href="{{ route('blog.show', $post->slug) }}" class="overflow-hidden position-relative d-block" style="height: 240px;">
                                 @if($post->image)
                                     <img src="{{ asset($post->image) }}" class="img-fluid w-100 h-100 object-fit-cover transition-scale" alt="{{ $post->getTranslation('title', app()->getLocale()) }}">
                                 @else
                                     <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-muted">
                                         <i class="fas fa-image fa-3x opacity-25"></i>
                                     </div>
                                 @endif

                                 {{-- Kateqoriya Etiketi --}}
                                 @if($post->category)
                                     <span class="badge bg-primary position-absolute top-0 start-0 m-3 shadow-sm">
                                         {{ $post->category->getTranslation('name', app()->getLocale()) }}
                                     </span>
                                 @endif
                             </a>

                             {{-- Məlumatlar --}}
                             <div class="card-body p-4 d-flex flex-column">
                                 <div class="small text-muted mb-2 d-flex align-items-center">
                                     <i class="far fa-calendar-alt me-2 text-primary"></i>
                                     {{ $post->created_at->format('d M, Y') }}

                                     <span class="mx-2">•</span>

                                     <i class="far fa-eye me-2 text-primary"></i>
                                     {{ $post->views }}
                                 </div>

                                 <h4 class="card-title fw-bold mb-3">
                                     <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none hover-underline">
                                         {{ $post->getTranslation('title', app()->getLocale()) }}
                                     </a>
                                 </h4>

                                 <p class="card-text text-muted mb-4 flex-grow-1" style="line-height: 1.6;">
                                     {{ Str::limit(strip_tags($post->getTranslation('content', app()->getLocale())), 100) }}
                                 </p>

                                 <a href="{{ route('blog.show', $post->slug) }}" class="fw-bold text-primary text-decoration-none group-hover-link d-inline-flex align-items-center">
                                     {{ __('blog.read_more', ['default' => 'Davamını oxu']) }}
                                     <i class="fas fa-arrow-right ms-2 transition-transform"></i>
                                 </a>
                             </div>
                         </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3">
                            <i class="far fa-newspaper fa-4x text-muted opacity-25"></i>
                        </div>
                        <h4 class="text-muted">{{ __('blog.no_posts', ['default' => 'Hələlik məqalə yoxdur.']) }}</h4>
                    </div>
                @endforelse
            </div>

            {{-- Paginasiya --}}
            @if($posts->hasPages())
                <div class="mt-5 d-flex justify-content-center custom-pagination">
                    {{ $posts->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>

        {{-- SAĞ TƏRƏF: Yan Panel (Sidebar) --}}
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 100px; z-index: 1;">

                {{-- Axtarış --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">{{ __('blog.search', ['default' => 'Axtarış']) }}</h5>
                        <form action="{{ route('blog.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control border-end-0 bg-light border-light"
                                       placeholder="{{ __('blog.search_placeholder', ['default' => 'Məqalə axtar...']) }}"
                                       value="{{ request('q') }}">
                                <button class="btn btn-light border-start-0 text-muted" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Kateqoriyalar --}}
                @if($categories->count() > 0)
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">{{ __('blog.categories', ['default' => 'Kateqoriyalar']) }}</h5>
                            <ul class="list-unstyled mb-0">
                                @foreach($categories as $cat)
                                    <li class="mb-2">
                                        <a href="{{ route('blog.index', ['category' => $cat->id]) }}"
                                           class="d-flex justify-content-between align-items-center text-decoration-none text-secondary hover-primary py-1 px-2 rounded transition-bg {{ request('category') == $cat->id ? 'bg-primary-subtle text-primary fw-bold' : '' }}">
                                            <span>{{ $cat->getTranslation('name', app()->getLocale()) }}</span>
                                            <span class="badge bg-light text-dark border rounded-pill">{{ $cat->posts_count }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- Son Məqalələr --}}
                @if($recent_posts->count() > 0)
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">{{ __('blog.recent_posts', ['default' => 'Son Məqalələr']) }}</h5>
                            @foreach($recent_posts as $recent)
                                <div class="d-flex mb-3 align-items-center">
                                    <a href="{{ route('blog.show', $recent->slug) }}" class="flex-shrink-0 me-3">
                                        @if($recent->image)
                                            <img src="{{ asset($recent->image) }}" class="rounded-3 object-fit-cover" style="width: 70px; height: 70px;" alt="...">
                                        @else
                                            <div class="rounded-3 bg-light d-flex align-items-center justify-content-center text-muted" style="width: 70px; height: 70px;">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </a>
                                    <div>
                                        <h6 class="mb-1 small fw-bold" style="line-height: 1.4;">
                                            <a href="{{ route('blog.show', $recent->slug) }}" class="text-dark text-decoration-none line-clamp-2 hover-primary">
                                                {{ $recent->getTranslation('title', app()->getLocale()) }}
                                            </a>
                                        </h6>
                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            <i class="far fa-clock me-1"></i> {{ $recent->created_at->format('d M, Y') }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Teqlər --}}
                @if($tags->count() > 0)
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">{{ __('blog.tags', ['default' => 'Teqlər']) }}</h5>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($tags as $tag)
                                    <a href="{{ route('blog.index', ['tag' => $tag->id]) }}"
                                       class="badge bg-light text-dark border text-decoration-none fw-normal py-2 px-3 hover-badge {{ request('tag') == $tag->id ? 'bg-primary text-white border-primary' : '' }}">
                                        #{{ $tag->getTranslation('name', app()->getLocale()) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<style>
    .transition-scale { transition: transform 0.5s ease; }
    .hover-card:hover .transition-scale { transform: scale(1.08); }
    .hover-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }

    .group-hover-link:hover .transition-transform { transform: translateX(5px); }
    .transition-transform { transition: transform 0.2s ease; }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .hover-primary:hover { color: #0d6efd !important; }
    .transition-bg { transition: background-color 0.2s; }
    .hover-badge:hover { background-color: #0d6efd !important; color: white !important; border-color: #0d6efd !important; }

    /* Pagination Style (Digər səhifələrlə eyni) */
    .custom-pagination .page-link {
        border-radius: 50px !important; margin: 0 5px; border: none; color: #333; font-weight: 600; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .custom-pagination .page-item.active .page-link { background-color: #0d6efd; color: white; box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3); }
    .custom-pagination .page-link:hover { background-color: #e9ecef; color: #0d6efd; }
</style>

@endsection
