@extends('layouts.public')

@section('title', $page->getTranslation('title', app()->getLocale()))

@section('content')

{{-- Hero Section --}}
<div class="bg-primary bg-opacity-10 py-5">
    <div class="container text-center">
        <h1 class="fw-bold display-5 mb-2 text-dark">{{ $page->getTranslation('title', app()->getLocale()) }}</h1>
        <p class="lead text-muted">{{ $page->getTranslation('content', app()->getLocale()) }}</p>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">{{ __('home.home', ['default' => 'Ana Səhifə']) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('service.services', ['default' => 'Xidmətlər']) }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">

    {{-- Axtarış Paneli --}}
    <div class="row justify-content-center mb-5">
        <div class="col-lg-6">
            <form action="{{ route('services') }}" method="GET">
                <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white border">
                    <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control border-0 shadow-none"
                           placeholder="{{ __('service.search_placeholder', ['default' => 'Xidmət adı axtar...']) }}"
                           value="{{ request('q') }}">
                    <button class="btn btn-primary px-4 border-0" type="submit">{{ __('service.search_btn', ['default' => 'Axtar']) }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Xidmətlər Grid --}}
    <div class="row g-4">
        @forelse($services as $service)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 border-0 shadow-sm hover-card rounded-4 overflow-hidden">
                    <div class="position-relative">
                        <a href="{{ route('service.show', $service->slug) }}">
                            @if($service->image)
                                <img src="{{ asset($service->image) }}" class="card-img-top object-fit-cover transition-scale"
                                     style="height: 200px;" alt="{{ $service->getTranslation('name', app()->getLocale()) }}">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center transition-scale" style="height: 200px;">
                                    <i class="fas fa-stethoscope fa-3x text-muted opacity-25"></i>
                                </div>
                            @endif
                        </a>
                        @if($service->price)
                            <span class="position-absolute bottom-0 end-0 bg-white text-primary px-3 py-1 m-3 rounded-pill fw-bold shadow-sm border border-light">
                                {{ $service->price }} ₼
                            </span>
                        @endif
                    </div>

                    <div class="card-body p-4 d-flex flex-column">
                        <h5 class="fw-bold mb-2">
                            <a href="{{ route('service.show', $service->slug) }}" class="text-dark text-decoration-none hover-primary">
                                {{ $service->getTranslation('name', app()->getLocale()) }}
                            </a>
                        </h5>

                        <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.6;">
                            {{ Str::limit($service->getTranslation('short_description', app()->getLocale()), 80) }}
                        </p>

                        <div class="d-grid">
                            <a href="{{ route('service.show', $service->slug) }}" class="btn btn-outline-primary rounded-pill btn-sm fw-bold">
                                {{ __('service.view_details', ['default' => 'Ətraflı Bax']) }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-notes-medical fa-4x text-muted opacity-25"></i>
                </div>
                <h4 class="text-muted">{{ __('service.no_results', ['default' => 'Xidmət tapılmadı']) }}</h4>
                <a href="{{ route('services') }}" class="btn btn-link text-decoration-none mt-2">{{ __('service.show_all', ['default' => 'Bütün Xidmətlər']) }}</a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($services->hasPages())
        <div class="d-flex justify-content-center mt-5 custom-pagination">
            {{ $services->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .hover-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }

    .transition-scale { transition: transform 0.5s ease; }
    .hover-card:hover .transition-scale { transform: scale(1.05); }

    .hover-primary:hover { color: #0d6efd !important; }

    /* Pagination Style */
    .custom-pagination .page-link {
        border-radius: 50px !important; margin: 0 5px; border: none; color: #333; font-weight: 600; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .custom-pagination .page-item.active .page-link { background-color: #0d6efd; color: white; box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3); }
    .custom-pagination .page-link:hover { background-color: #e9ecef; color: #0d6efd; }
</style>

@endsection
