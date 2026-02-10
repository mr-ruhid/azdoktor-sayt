@extends('layouts.public')

@section('title', $page->getTranslation('title', app()->getLocale()))

@section('content')

{{-- 1. HERO SECTION --}}
<section class="bg-primary bg-opacity-10 py-5 position-relative overflow-hidden">
    <div class="container text-center position-relative z-2">
        <h1 class="fw-bold display-5 mb-3">{{ $page->getTranslation('title', app()->getLocale()) }}</h1>
        <div class="lead text-muted mx-auto" style="max-width: 700px;">
            {!! $page->getTranslation('content', app()->getLocale()) !!}
        </div>

        <nav aria-label="breadcrumb" class="mt-4">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">{{ __('home.home', ['default' => 'Ana Səhifə']) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('home.faq', ['default' => 'Sual-Cavab']) }}</li>
            </ol>
        </nav>
    </div>

    {{-- Arxa fon dekoru --}}
    <div class="position-absolute top-0 end-0 opacity-25">
        <i class="fas fa-question-circle fa-10x text-primary transform-rotate-12"></i>
    </div>
</section>

{{-- 2. FAQ SİYAHISI --}}
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            @php
                $faqItems = $page->getMeta('faq_items', []);
            @endphp

            @if(!empty($faqItems))
                <div class="accordion accordion-flush shadow-sm rounded-4 overflow-hidden border" id="faqAccordion">
                    @foreach($faqItems as $index => $item)
                        @php
                            $question = $item['question'][app()->getLocale()] ?? '';
                            $answer = $item['answer'][app()->getLocale()] ?? '';
                        @endphp

                        @if($question && $answer)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button class="accordion-button {{ $index != 0 ? 'collapsed' : '' }} fw-bold py-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                        <i class="far fa-question-circle me-3 text-primary fa-lg"></i> {{ $question }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted lh-lg pb-4 px-4">
                                        {!! nl2br(e($answer)) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="far fa-folder-open fa-4x text-muted opacity-25"></i>
                    </div>
                    <h4 class="text-muted">{{ __('faq.no_questions', ['default' => 'Hələlik sual-cavab əlavə edilməyib.']) }}</h4>
                </div>
            @endif

        </div>
    </div>
</div>

{{-- 3. ƏLAQƏ ÇAĞIRIŞI --}}
<section class="py-5 bg-light">
    <div class="container text-center">
        <h3 class="fw-bold mb-3">{{ __('faq.more_questions', ['default' => 'Başqa sualınız var?']) }}</h3>
        <p class="text-muted mb-4">{{ __('faq.contact_text', ['default' => 'Axtardığınız sualın cavabını tapmadınızsa, bizimlə birbaşa əlaqə saxlaya bilərsiniz.']) }}</p>
        <a href="{{ route('contact') }}" class="btn btn-primary rounded-pill px-5 py-3 shadow-sm btn-hover-scale">
            <i class="fas fa-envelope me-2"></i> {{ __('faq.contact_us', ['default' => 'Bizə Yazın']) }}
        </a>
    </div>
</section>

<style>
    .accordion-button:not(.collapsed) {
        background-color: rgba(13, 110, 253, 0.05);
        color: #0d6efd;
        box-shadow: none;
    }
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }
    .transform-rotate-12 {
        transform: rotate(12deg) translate(20px, -20px);
    }
    .btn-hover-scale:hover {
        transform: scale(1.05);
        transition: transform 0.2s;
    }
</style>

@endsection
