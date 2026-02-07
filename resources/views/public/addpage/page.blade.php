@extends('layouts.public')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Səhifə Başlığı -->
            <h1 class="fw-bold mb-4 text-center">{{ $page->title }}</h1>

            <!-- Qapaq Şəkli (Əgər varsa) -->
            @if($page->image)
                <div class="mb-4 text-center">
                    <img src="{{ asset($page->image) }}" class="img-fluid rounded shadow-sm w-100" style="max-height: 400px; object-fit: cover;">
                </div>
            @endif

            <!-- Məzmun -->
            <div class="content-body bg-white p-4 rounded shadow-sm">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</div>
@endsection@extends('layouts.public')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Səhifə Başlığı -->
            <h1 class="fw-bold mb-4 text-center">{{ $page->title }}</h1>

            <!-- Qapaq Şəkli (Əgər varsa) -->
            @if($page->image)
                <div class="mb-4 text-center">
                    <img src="{{ asset($page->image) }}" class="img-fluid rounded shadow-sm w-100" style="max-height: 400px; object-fit: cover;">
                </div>
            @endif

            <!-- Məzmun -->
            <div class="content-body bg-white p-4 rounded shadow-sm">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</div>
@endsection
