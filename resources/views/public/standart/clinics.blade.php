@extends('layouts.public')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">{{ $page->title }}</h1>

    <div class="row">
        @foreach($clinics as $clinic)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm border-0 hover-shadow">
                <div class="position-relative">
                    @if($clinic->image)
                        <img src="{{ asset($clinic->image) }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                            <i class="fas fa-hospital fa-3x text-secondary"></i>
                        </div>
                    @endif
                    <span class="position-absolute top-0 end-0 bg-success text-white px-2 py-1 m-2 rounded small">Açıqdır</span>
                </div>
                <div class="card-body">
                    <h5 class="card-title text-truncate">{{ $clinic->name }}</h5>
                    <p class="small text-muted mb-2"><i class="fas fa-map-marker-alt me-1"></i> {{ Str::limit($clinic->address, 30) }}</p>
                    <div class="d-grid mt-3">
                        <a href="#" class="btn btn-outline-primary btn-sm rounded-pill">Randevu Al</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $clinics->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
