@extends('layouts.public')

@section('title', $page->getTranslation('title', app()->getLocale()))

@section('content')

{{-- 1. HERO SECTION --}}
<section class="bg-light py-5 position-relative">
    <div class="container text-center py-4">
        <h1 class="fw-bold display-4 mb-3">{{ $page->getTranslation('title', app()->getLocale()) }}</h1>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <p class="lead text-muted">{{ $page->getTranslation('content', app()->getLocale()) }}</p>
            </div>
        </div>
    </div>
</section>

{{-- 2. DİNAMİK BLOKLAR (ZİQ-ZAQ) --}}
@php
    $sections = $page->getMeta('sections', []);
@endphp

@if(!empty($sections))
    <div class="container py-5">
        @foreach($sections as $index => $section)
            @php
                // Tək ədədlər (1, 3, 5) => Şəkil Solda
                // Cüt ədədlər (2, 4, 6) => Şəkil Sağda (reverse)
                $isReverse = ($index % 2 != 0);

                // Cari dilə uyğun məlumatlar
                $title = $section['title'][app()->getLocale()] ?? '';
                $content = $section['content'][app()->getLocale()] ?? '';
                $image = !empty($section['image']) ? asset($section['image']) : 'https://placehold.co/600x400?text=AzDoktor';
            @endphp

            @if($title || $content)
                <div class="row align-items-center mb-5 pb-4 {{ $loop->last ? '' : 'border-bottom' }}">

                    {{-- Şəkil Hissəsi --}}
                    <div class="col-lg-6 mb-4 mb-lg-0 {{ $isReverse ? 'order-lg-2 ms-lg-auto' : 'order-lg-1' }}">
                        <div class="position-relative">
                            {{-- Dekorativ çərçivə --}}
                            <div class="position-absolute bg-primary rounded opacity-10"
                                 style="width: 100%; height: 100%; top: 20px; left: {{ $isReverse ? '20px' : '-20px' }}; z-index: -1;"></div>

                            <img src="{{ $image }}" class="img-fluid rounded-4 shadow-lg w-100 object-fit-cover"
                                 style="min-height: 350px; max-height: 450px;" alt="{{ $title }}">
                        </div>
                    </div>

                    {{-- Mətn Hissəsi --}}
                    <div class="col-lg-5 {{ $isReverse ? 'order-lg-1 me-lg-auto' : 'order-lg-2 ms-lg-auto' }}">
                        <div class="ps-lg-4">
                            <span class="badge bg-primary-subtle text-primary mb-2 rounded-pill px-3">0{{ $index + 1 }}</span>
                            <h2 class="fw-bold mb-3">{{ $title }}</h2>
                            <div class="text-muted text-break" style="line-height: 1.8;">
                                {!! nl2br(e($content)) !!}
                            </div>
                        </div>
                    </div>

                </div>
            @endif
        @endforeach
    </div>
@else
    {{-- Əgər blok yoxdursa standart bir məlumat göstərək --}}
    <div class="container py-5 text-center">
        <p class="text-muted">Hələlik əlavə məlumat daxil edilməyib.</p>
    </div>
@endif

@endsection
