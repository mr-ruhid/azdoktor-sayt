@extends('layouts.public')

@section('title', $post->getTranslation('title', app()->getLocale()))

@section('content')

<div class="container py-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">{{ __('home.home', ['default' => 'Ana Səhifə']) }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blog.index') }}" class="text-decoration-none text-muted">{{ __('blog.blog_title', ['default' => 'Bloq']) }}</a></li>
            @if($post->category)
                <li class="breadcrumb-item">
                    <a href="{{ route('blog.index', ['category' => $post->category->id]) }}" class="text-decoration-none text-muted">
                        {{ $post->category->getTranslation('name', app()->getLocale()) }}
                    </a>
                </li>
            @endif
            <li class="breadcrumb-item active text-primary" aria-current="page">{{ Str::limit($post->getTranslation('title', app()->getLocale()), 30) }}</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-9">

            {{-- 1. Kateqoriya və Tarix (Yuxarıda) --}}
            <div class="text-center mb-3">
                @if($post->category)
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill text-uppercase ls-1 mb-3">
                        {{ $post->category->getTranslation('name', app()->getLocale()) }}
                    </span>
                @endif
                <div class="text-muted small">
                    <i class="far fa-calendar-alt me-1"></i> {{ $post->created_at->format('d M, Y') }}
                    <span class="mx-2">•</span>
                    <i class="far fa-eye me-1"></i> {{ $post->views ?? 0 }} {{ __('blog.views', ['default' => 'baxış']) }}
                </div>
            </div>

            {{-- 2. Qapaq Şəkli (Başda) --}}
            @if($post->image)
                <div class="mb-4 overflow-hidden rounded-4 shadow-sm">
                    <img src="{{ asset($post->image) }}" class="img-fluid w-100 object-fit-cover"
                         style="max-height: 500px;" alt="{{ $post->getTranslation('title', app()->getLocale()) }}">
                </div>
            @endif

            {{-- 3. Başlıq --}}
            <h1 class="fw-bold display-5 mb-4 text-center text-dark lh-sm">
                {{ $post->getTranslation('title', app()->getLocale()) }}
            </h1>

            {{-- 4. Məzmun (Article Body) --}}
            <article class="article-content fs-5 text-dark mb-5">
                {{-- CKEditor-dan gələn HTML formatını qoruyuruq --}}
                {!! $post->getTranslation('content', app()->getLocale()) !!}
            </article>

            {{-- 5. Teqlər və Paylaşım --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center border-top border-bottom py-4 mb-5">
                {{-- Teqlər --}}
                <div class="mb-3 mb-md-0">
                    @if($post->tags->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($post->tags as $tag)
                                <span class="badge bg-light text-dark border fw-normal px-3 py-2">
                                    #{{ $tag->getTranslation('name', app()->getLocale()) }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Paylaşım --}}
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold small text-uppercase text-muted me-2">{{ __('blog.share', ['default' => 'Paylaş:']) }}</span>
                    <a href="#" class="btn btn-sm btn-outline-primary rounded-circle social-share"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-sm btn-outline-info rounded-circle social-share"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="btn btn-sm btn-outline-success rounded-circle social-share"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            {{-- 6. Şərhlər Bölməsi --}}
            <div class="bg-light p-4 p-md-5 rounded-4" id="comments">
                <h3 class="fw-bold mb-4">{{ __('blog.reviews', ['default' => 'Rəylər']) }} ({{ $post->comments->where('is_approved', true)->count() }})</h3>

                {{-- Şərh Formu --}}
                <div class="card border-0 shadow-sm mb-5">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">{{ __('blog.leave_comment', ['default' => 'Fikrinizi bildirin']) }}</h6>
                        <form action="{{ route('comment.submit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="commentable_id" value="{{ $post->id }}">
                            <input type="hidden" name="commentable_type" value="App\Models\Post">

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control bg-light border-0" placeholder="{{ __('blog.your_name', ['default' => 'Adınız']) }}" required value="{{ Auth::check() ? Auth::user()->name : '' }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="email" name="email" class="form-control bg-light border-0" placeholder="{{ __('blog.email', ['default' => 'E-poçt']) }}" required value="{{ Auth::check() ? Auth::user()->email : '' }}">
                                </div>
                            </div>

                            {{-- Blog üçün reytinq --}}
                            <div class="mb-3">
                                <div class="rating-stars">
                                    <input type="radio" name="rating" value="5" id="s5"><label for="s5">☆</label>
                                    <input type="radio" name="rating" value="4" id="s4"><label for="s4">☆</label>
                                    <input type="radio" name="rating" value="3" id="s3"><label for="s3">☆</label>
                                    <input type="radio" name="rating" value="2" id="s2"><label for="s2">☆</label>
                                    <input type="radio" name="rating" value="1" id="s1"><label for="s1">☆</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <textarea name="content" class="form-control bg-light border-0" rows="3" placeholder="{{ __('blog.your_review', ['default' => 'Rəyiniz...']) }}" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary rounded-pill px-4">{{ __('blog.submit', ['default' => 'Göndər']) }}</button>
                        </form>
                    </div>
                </div>

                {{-- Şərhlər Siyahısı --}}
                @forelse($post->comments->where('is_approved', true) as $comment)
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="bg-white border text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 50px; height: 50px; font-size: 20px;">
                                {{ substr($comment->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="bg-white p-3 rounded-3 shadow-sm border-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0 text-dark">{{ $comment->name }}</h6>
                                    <small class="text-muted">{{ $comment->created_at->format('d M, Y') }}</small>
                                </div>
                                @if($comment->rating)
                                    <div class="text-warning small mb-2">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="{{ $i <= $comment->rating ? 'fas' : 'far' }} fa-star"></i>
                                        @endfor
                                    </div>
                                @endif
                                <p class="mb-0 text-secondary">{{ $comment->content }}</p>
                            </div>

                            {{-- Admin Cavabı --}}
                            @if($comment->replies->count() > 0)
                                <div class="mt-2 ms-4 d-flex">
                                    <div class="flex-shrink-0 me-2">
                                        <i class="fas fa-reply fa-rotate-180 text-muted"></i>
                                    </div>
                                    <div class="bg-primary-subtle p-3 rounded-3 flex-grow-1">
                                        <small class="fw-bold text-primary d-block mb-1">{{ __('blog.admin', ['default' => 'Admin']) }}</small>
                                        <p class="small mb-0 text-dark">{{ $comment->replies->first()->content }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <p class="text-muted mb-0">{{ __('blog.no_comments', ['default' => 'Hələ heç bir rəy yoxdur. İlk yazan siz olun!']) }}</p>
                    </div>
                @endforelse

            </div>

        </div>
    </div>
</div>

<style>
    /* Məqalə içindəki formatlar */
    .article-content { color: #333; }
    .article-content p { margin-bottom: 1.5rem; }
    .article-content img { max-width: 100%; height: auto; border-radius: 10px; margin: 20px 0; }
    .article-content h2 { font-weight: bold; margin-top: 2rem; margin-bottom: 1rem; color: #2c3e50; }
    .article-content ul, .article-content ol { margin-bottom: 1.5rem; padding-left: 1.5rem; }
    .article-content blockquote { border-left: 4px solid #0d6efd; padding-left: 1rem; font-style: italic; color: #555; background: #f8f9fa; padding: 15px; border-radius: 0 10px 10px 0; }

    /* Sosial Paylaşım Düymələri */
    .social-share { width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; transition: all 0.3s; }
    .social-share:hover { transform: translateY(-3px); }

    /* Rating Input Style */
    .rating-stars { display: flex; flex-direction: row-reverse; justify-content: flex-end; }
    .rating-stars input { display: none; }
    .rating-stars label { font-size: 24px; color: #ddd; cursor: pointer; transition: color 0.2s; }
    .rating-stars input:checked ~ label, .rating-stars label:hover, .rating-stars label:hover ~ label { color: #ffc107; }

    .ls-1 { letter-spacing: 1px; }
</style>
@endsection
