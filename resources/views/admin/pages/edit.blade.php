@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Redaktə Et: {{ $page->getTranslation('title', app()->getLocale()) }}</h1>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri
        </a>
    </div>

    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            {{-- SOL TƏRƏF --}}
            <div class="col-md-8">
                {{-- Əsas Məzmun --}}
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Səhifə Məzmunu</h6>
                    </div>
                    <div class="card-body">
                        {{-- Dil Tabları (Dinamik) --}}
                        <ul class="nav nav-tabs mb-3" id="langTab" role="tablist">
                            @foreach($languages as $index => $lang)
                                <li class="nav-item">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                            data-bs-toggle="tab"
                                            data-bs-target="#{{ $lang->code }}"
                                            type="button">
                                        <img src="{{ asset($lang->flag) }}" width="20" class="me-1"> {{ $lang->name }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Tab Məzmunu (Dinamik) --}}
                        <div class="tab-content" id="langTabContent">
                            @foreach($languages as $index => $lang)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="{{ $lang->code }}">
                                    <div class="mb-3">
                                        <label>Başlıq ({{ $lang->name }})</label>
                                        <input type="text" name="title[{{ $lang->code }}]" class="form-control"
                                               value="{{ $page->getTranslation('title', $lang->code, false) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label>Məzmun ({{ $lang->name }})</label>
                                        <textarea name="content[{{ $lang->code }}]" class="form-control" rows="5">{{ $page->getTranslation('content', $lang->code, false) }}</textarea>
                                        <small class="text-muted">Ana səhifə üçün bu hissə "Hero" altındakı yazı ola bilər.</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ANA SƏHİFƏ ÜÇÜN XÜSUSİ AYARLAR --}}
                @if($page->slug == 'home')
                <div class="card shadow mb-4 border-left-primary">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-home me-2"></i> Ana Səhifə Parametrləri</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="fw-bold">Hero Banner (Arxa Fon Şəkli)</label>
                            <input type="file" name="banner_image" class="form-control mb-2">
                            @if($page->getMeta('banner_image'))
                                <div class="p-2 border rounded bg-light">
                                    <img src="{{ asset($page->getMeta('banner_image')) }}" height="100" class="rounded">
                                    <div class="small text-muted mt-1">Cari Şəkil</div>
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-bold">Görünəcək Həkim Sayı</label>
                                <input type="number" name="doctor_count" class="form-control" value="{{ $page->getMeta('doctor_count', 8) }}">
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- SAĞ TƏRƏF --}}
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Yayımlama</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="status" id="status" {{ $page->status ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Aktivdir</label>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Yadda Saxla</button>
                    </div>
                </div>

                {{-- SEO Ayarları --}}
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">SEO Ayarları</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label>SEO Başlıq (Title)</label>
                            <input type="text" name="seo_title" class="form-control" value="{{ $page->seo_title }}">
                        </div>
                        <div class="mb-3">
                            <label>SEO Açıqlama (Description)</label>
                            <textarea name="seo_desc" class="form-control" rows="3">{{ $page->seo_description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label>Paylaşım Şəkli (OG Image)</label>
                            <input type="file" name="image" class="form-control mb-2">
                            @if($page->image)
                                <img src="{{ asset($page->image) }}" class="img-fluid rounded border">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
