@extends('layouts.public')

@section('content')

<!-- Hero Banner (Admin paneldən yüklənən şəkil) -->
<div class="card bg-dark text-white border-0 rounded-4 overflow-hidden mb-5">
    @if($page->image)
        <img src="{{ asset($page->image) }}" class="card-img" alt="Banner" style="height: 300px; object-fit: cover; opacity: 0.6;">
    @else
        <div style="height: 300px; background: linear-gradient(45deg, #007bff, #00d2ff);"></div>
    @endif
    <div class="card-img-overlay d-flex align-items-center justify-content-center">
        <div class="text-center">
            <h1 class="display-4 fw-bold">{{ $page->title }}</h1>
            <p class="lead">{{ strip_tags($page->content) }}</p> {{-- HTML teqləri təmizləyirik --}}
            <a href="{{ route('clinics') }}" class="btn btn-light btn-lg rounded-pill px-5 mt-3">Klinikaları Kəşf Et</a>
        </div>
    </div>
</div>

<!-- Klinikalar Bloku -->
<h3 class="mb-4">Populyar Klinikalar</h3>
<div class="row">
    @foreach($clinics as $clinic)
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0">
            @if($clinic->image)
                <img src="{{ asset($clinic->image) }}" class="card-img-top" style="height: 180px; object-fit: cover;">
            @endif
            <div class="card-body">
                <h5 class="card-title">{{ $clinic->name }}</h5>
                <p class="card-text text-muted small"><i class="fas fa-map-marker-alt me-1"></i> {{ $clinic->address }}</p>
                <a href="#" class="btn btn-outline-primary btn-sm rounded-pill w-100">Detallı Bax</a>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection
