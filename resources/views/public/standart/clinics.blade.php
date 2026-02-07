@extends('layouts.public')

@section('title', $page->getTranslation('title', app()->getLocale()))

@section('content')
<div class="container py-5">

    {{-- Başlıq və Axtarış Hissəsi --}}
    <div class="row mb-5 align-items-center gy-3">
        <div class="col-md-6">
            <h1 class="fw-bold mb-0">{{ $page->getTranslation('title', app()->getLocale()) }}</h1>
            <p class="text-muted mt-1 mb-0">{{ $clinics->total() }} {{ __('clinics.found_text', ['default' => 'klinika mövcuddur']) }}</p>
        </div>

        <div class="col-md-6">
            <form action="{{ route('clinics') }}" method="GET">
                <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white border">
                    <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control border-0 shadow-none"
                           placeholder="{{ __('clinics.search_placeholder', ['default' => 'Klinika adı və ya ünvan axtar...']) }}"
                           value="{{ request('q') }}">
                    <button class="btn btn-primary px-4 border-0" type="submit">{{ __('clinics.search_btn', ['default' => 'Axtar']) }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Klinikalar Grid --}}
    <div class="row g-4">
        @forelse($clinics as $clinic)
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0 hover-shadow rounded-4 overflow-hidden clinic-card">
                    <div class="position-relative">
                        {{-- Şəkil --}}
                        @if($clinic->image)
                            <img src="{{ asset($clinic->image) }}" class="card-img-top object-fit-cover" style="height: 180px;" alt="{{ $clinic->getTranslation('name', app()->getLocale()) }}">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                <i class="fas fa-hospital fa-3x text-secondary opacity-25"></i>
                            </div>
                        @endif

                        {{-- Status Etiketi --}}
                        <span class="position-absolute top-0 end-0 bg-success text-white px-3 py-1 m-3 rounded-pill small fw-bold shadow-sm">
                            {{ __('clinics.open_status', ['default' => 'Açıqdır']) }}
                        </span>
                    </div>

                    <div class="card-body p-3 d-flex flex-column">
                        <h5 class="card-title text-truncate fw-bold text-dark">
                            {{ $clinic->getTranslation('name', app()->getLocale()) }}
                        </h5>

                        <p class="small text-muted mb-3">
                            <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                            {{ Str::limit($clinic->getTranslation('address', app()->getLocale()), 40) }}
                        </p>

                        <div class="mt-auto d-grid">
                            <a href="#" class="btn btn-outline-primary rounded-pill btn-sm fw-bold">
                                {{ __('clinics.book_appointment', ['default' => 'Randevu Al']) }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-hospital-alt text-muted fa-4x opacity-50"></i>
                </div>
                <h4 class="text-muted">{{ __('clinics.no_results', ['default' => 'Klinika tapılmadı']) }}</h4>
                <a href="{{ route('clinics') }}" class="btn btn-link text-decoration-none">{{ __('clinics.show_all', ['default' => 'Hamısını göstər']) }}</a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($clinics->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $clinics->links() }}
        </div>
    @endif
</div>

<style>
    .object-fit-cover { object-fit: cover; }
    .clinic-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .clinic-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
</style>
@endsection
