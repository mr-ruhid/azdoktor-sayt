@extends('layouts.public')

@section('title', $page->getTranslation('title', app()->getLocale()))

@section('content')

{{-- 1. HERO SECTION (Başlıq) --}}
<section class="hero-section position-relative py-5 mb-5 d-flex align-items-center" style="min-height: 300px;">
    {{-- Arxa Fon Şəkli (Əgər varsa, yoxsa default) --}}
    @php
        $bannerImage = $page->getMeta('banner_image') ? asset($page->getMeta('banner_image')) : 'https://img.freepik.com/free-photo/abstract-blur-hospital-clinic-interior_1203-9764.jpg';
    @endphp

    <div class="position-absolute top-0 start-0 w-100 h-100"
         style="background: url('{{ $bannerImage }}') no-repeat center center/cover;">
         {{-- Qradiyent --}}
         <div class="position-absolute top-0 start-0 w-100 h-100"
              style="background: linear-gradient(135deg, rgba(13, 110, 253, 0.9) 0%, rgba(13, 202, 240, 0.75) 100%);"></div>
    </div>

    <div class="container position-relative z-2 text-center">
        <h1 class="fw-bold display-5 text-white mb-3">{{ $page->getTranslation('title', app()->getLocale()) }}</h1>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white text-decoration-none opacity-75">{{ __('home.home', ['default' => 'Ana Səhifə']) }}</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">{{ $page->getTranslation('title', app()->getLocale()) }}</li>
            </ol>
        </nav>
    </div>

    {{-- Dalğa Effekti --}}
    <div class="position-absolute bottom-0 start-0 w-100 overflow-hidden" style="line-height: 0; z-index: 3;">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="width: 100%; height: 50px; fill: #f8f9fa; display: block;">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"></path>
        </svg>
    </div>
</section>

{{-- 2. MƏZMUN HİSSƏSİ --}}
<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <div class="card-body article-content">
                    {{-- Şəkil (Əgər ayrıca 'image' sütununda varsa) --}}
                    @if($page->image)
                        <img src="{{ asset($page->image) }}" class="img-fluid rounded-4 mb-4 w-100 object-fit-cover" style="max-height: 400px;" alt="{{ $page->getTranslation('title', app()->getLocale()) }}">
                    @endif

                    {{-- HTML Məzmun --}}
                    {!! $page->getTranslation('content', app()->getLocale()) !!}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Səhifədaxili Stil (Məzmun formatlanması üçün) --}}
<style>
    .article-content { color: #333; font-size: 1.05rem; line-height: 1.8; }
    .article-content h2, .article-content h3 { font-weight: bold; margin-top: 2rem; margin-bottom: 1rem; color: #2c3e50; }
    .article-content p { margin-bottom: 1.5rem; }
    .article-content ul, .article-content ol { margin-bottom: 1.5rem; padding-left: 1.5rem; }
    .article-content img { max-width: 100%; height: auto; border-radius: 10px; margin: 20px 0; }
    .article-content a { color: #0d6efd; text-decoration: none; font-weight: 500; }
    .article-content a:hover { text-decoration: underline; }
    .article-content blockquote { border-left: 4px solid #0d6efd; padding-left: 1rem; font-style: italic; color: #555; background: #f8f9fa; padding: 15px; border-radius: 0 10px 10px 0; margin: 20px 0; }
</style>

@endsection
