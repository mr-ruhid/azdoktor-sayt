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
                {{-- Əsas Məzmun (Başlıq və Qısa Giriş) --}}
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Səhifə Başlığı və Giriş</h6>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3" id="langTab" role="tablist">
                            @foreach($languages as $index => $lang)
                                <li class="nav-item">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                            data-bs-toggle="tab" data-bs-target="#main-{{ $lang->code }}" type="button">
                                        <img src="{{ asset($lang->flag) }}" width="20"> {{ $lang->name }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content">
                            @foreach($languages as $index => $lang)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="main-{{ $lang->code }}">
                                    <div class="mb-3">
                                        <label>Başlıq ({{ $lang->name }})</label>
                                        <input type="text" name="title[{{ $lang->code }}]" class="form-control"
                                               value="{{ $page->getTranslation('title', $lang->code, false) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label>Giriş Mətni / Qısa Məzmun ({{ $lang->name }})</label>
                                        <textarea name="content[{{ $lang->code }}]" class="form-control" rows="4">{{ $page->getTranslation('content', $lang->code, false) }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- HAQQIMIZDA BLOKLARI (YALNIZ 'about' SƏHİFƏSİ ÜÇÜN) --}}
                @if($page->slug == 'about')
                <div class="card shadow mb-4 border-left-info">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-layer-group me-2"></i> Məzmun Blokları (Missiya, Vizyon və s.)</h6>
                        <button type="button" class="btn btn-sm btn-info" onclick="addSection()">
                            <i class="fas fa-plus"></i> Yeni Blok
                        </button>
                    </div>
                    <div class="card-body" id="sections-container">
                        @php
                            $sections = $page->getMeta('sections', []);
                        @endphp

                        @foreach($sections as $key => $section)
                            <div class="card mb-3 border section-item" id="section-{{ $key }}">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <span><strong>Blok #{{ $key + 1 }}</strong></span>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeSection('section-{{ $key }}')"><i class="fas fa-trash"></i></button>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Şəkil</label>
                                            <input type="file" name="sections[{{ $key }}][image]" class="form-control mb-2">
                                            <input type="hidden" name="sections[{{ $key }}][old_image]" value="{{ $section['image'] ?? '' }}">
                                            @if(!empty($section['image']))
                                                <img src="{{ asset($section['image']) }}" class="img-fluid rounded" style="max-height: 100px;">
                                            @endif
                                        </div>
                                        <div class="col-md-8">
                                            <!-- Daxili Tablar -->
                                            <ul class="nav nav-pills mb-2" id="pills-tab-{{ $key }}" role="tablist">
                                                @foreach($languages as $i => $l)
                                                    <li class="nav-item">
                                                        <button class="nav-link {{ $i === 0 ? 'active' : '' }} py-1 px-3 small"
                                                                data-bs-toggle="pill" data-bs-target="#sec-{{ $key }}-{{ $l->code }}" type="button">
                                                            {{ $l->code }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="tab-content">
                                                @foreach($languages as $i => $l)
                                                    <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="sec-{{ $key }}-{{ $l->code }}">
                                                        <input type="text" name="sections[{{ $key }}][title][{{ $l->code }}]" class="form-control mb-2 form-control-sm" placeholder="Başlıq ({{ $l->name }})" value="{{ $section['title'][$l->code] ?? '' }}">
                                                        <textarea name="sections[{{ $key }}][content][{{ $l->code }}]" class="form-control form-control-sm" rows="3" placeholder="Mətn ({{ $l->name }})">{{ $section['content'][$l->code] ?? '' }}</textarea>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- ANA SƏHİFƏ PARMETRLƏRİ (OLD) --}}
                @if($page->slug == 'home')
                <div class="card shadow mb-4 border-left-primary">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Ana Səhifə Parametrləri</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="fw-bold">Hero Banner</label>
                            <input type="file" name="banner_image" class="form-control mb-2">
                            @if($page->getMeta('banner_image'))
                                <img src="{{ asset($page->getMeta('banner_image')) }}" height="100" class="rounded border p-1">
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-bold">Həkim Sayı</label>
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

                {{-- SEO --}}
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">SEO Ayarları</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label>SEO Başlıq</label>
                            <input type="text" name="seo_title" class="form-control" value="{{ $page->seo_title }}">
                        </div>
                        <div class="mb-3">
                            <label>SEO Açıqlama</label>
                            <textarea name="seo_desc" class="form-control" rows="3">{{ $page->seo_description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label>Paylaşım Şəkli</label>
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

{{-- SCRIPT: Dynamic Section Builder --}}
@section('scripts')
<script>
    let sectionCount = {{ isset($sections) ? count($sections) : 0 }};
    const languages = @json($languages);

    function addSection() {
        let html = `
            <div class="card mb-3 border section-item" id="section-${sectionCount}">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <span><strong>Yeni Blok</strong></span>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeSection('section-${sectionCount}')"><i class="fas fa-trash"></i></button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Şəkil</label>
                            <input type="file" name="sections[${sectionCount}][image]" class="form-control">
                        </div>
                        <div class="col-md-8">
                            <ul class="nav nav-pills mb-2" role="tablist">
                                ${languages.map((lang, index) => `
                                    <li class="nav-item">
                                        <button class="nav-link ${index === 0 ? 'active' : ''} py-1 px-3 small"
                                                data-bs-toggle="pill" data-bs-target="#new-sec-${sectionCount}-${lang.code}" type="button">
                                            ${lang.code}
                                        </button>
                                    </li>
                                `).join('')}
                            </ul>
                            <div class="tab-content">
                                ${languages.map((lang, index) => `
                                    <div class="tab-pane fade ${index === 0 ? 'show active' : ''}" id="new-sec-${sectionCount}-${lang.code}">
                                        <input type="text" name="sections[${sectionCount}][title][${lang.code}]" class="form-control mb-2 form-control-sm" placeholder="Başlıq (${lang.name})">
                                        <textarea name="sections[${sectionCount}][content][${lang.code}]" class="form-control form-control-sm" rows="3" placeholder="Mətn (${lang.name})"></textarea>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('sections-container').insertAdjacentHTML('beforeend', html);
        sectionCount++;
    }

    function removeSection(id) {
        document.getElementById(id).remove();
    }
</script>
@endsection

@endsection
