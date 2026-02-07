@extends('layouts.public')

@section('content')

<div class="container py-4">
    <div class="text-center mb-5">
        <h1 class="fw-bold">{{ $page->title }}</h1>
        @if($page->image)
            <img src="{{ asset($page->image) }}" class="img-fluid rounded-4 mt-3 shadow" style="max-height: 400px; width: 100%; object-fit: cover;">
        @endif
    </div>

    <!-- Məzmun Bloku (Admin Paneldən gələn) -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="content-body fs-5 lh-lg text-secondary">
                {!! $page->content !!}
                {{-- Admin paneldəki editorla yazılan HTML bura düşür. --}}
                {{-- Blok effekti yaratmaq üçün Admin paneldə H2, P və H4 teqlərindən istifadə edin --}}
            </div>
        </div>
    </div>

    <!-- Statistikalar (Statik nümunə, gələcəkdə dinamikləşdirmək olar) -->
    <div class="row text-center mt-5">
        <div class="col-md-4">
            <div class="p-4 bg-white rounded shadow-sm">
                <h2 class="text-primary fw-bold">50+</h2>
                <p class="text-muted">Klinika</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 bg-white rounded shadow-sm">
                <h2 class="text-success fw-bold">200+</h2>
                <p class="text-muted">Həkim</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 bg-white rounded shadow-sm">
                <h2 class="text-warning fw-bold">10k+</h2>
                <p class="text-muted">İstifadəçi</p>
            </div>
        </div>
    </div>
</div>
@endsection
