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
                                        <label>Giriş Mətni / Üst Hissə ({{ $lang->name }})</label>
                                        <textarea name="content[{{ $lang->code }}]" class="form-control editor" rows="4">{{ $page->getTranslation('content', $lang->code, false) }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- HAQQIMIZDA BLOKLARI --}}
                @if($page->slug == 'about')
                <div class="card shadow mb-4 border-left-info">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-layer-group me-2"></i> Məzmun Blokları</h6>
                        <button type="button" class="btn btn-sm btn-info" onclick="addSection()">
                            <i class="fas fa-plus"></i> Yeni Blok
                        </button>
                    </div>
                    <div class="card-body" id="sections-container">
                        @php $sections = $page->getMeta('sections', []); @endphp
                        @foreach($sections as $key => $section)
                            <div class="card mb-3 border section-item" id="section-{{ $key }}">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <span><strong>Blok #{{ $key + 1 }}</strong></span>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeElement('section-{{ $key }}')"><i class="fas fa-trash"></i></button>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Şəkil</label>
                                            <input type="file" name="sections[{{ $key }}][image]" class="form-control mb-2">
                                            <input type="hidden" name="sections[{{ $key }}][old_image]" value="{{ $section['image'] ?? '' }}">
                                            @if(!empty($section['image'])) <img src="{{ asset($section['image']) }}" class="img-fluid rounded" style="max-height: 80px;"> @endif
                                        </div>
                                        <div class="col-md-8">
                                            <ul class="nav nav-pills mb-2">
                                                @foreach($languages as $i => $l)
                                                    <li class="nav-item">
                                                        <button class="nav-link {{ $i === 0 ? 'active' : '' }} py-1 px-2 small" data-bs-toggle="pill" data-bs-target="#sec-{{ $key }}-{{ $l->code }}" type="button">{{ $l->code }}</button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="tab-content">
                                                @foreach($languages as $i => $l)
                                                    <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="sec-{{ $key }}-{{ $l->code }}">
                                                        <input type="text" name="sections[{{ $key }}][title][{{ $l->code }}]" class="form-control mb-2 form-control-sm" placeholder="Başlıq" value="{{ $section['title'][$l->code] ?? '' }}">
                                                        <textarea name="sections[{{ $key }}][content][{{ $l->code }}]" class="form-control form-control-sm" rows="3" placeholder="Mətn">{{ $section['content'][$l->code] ?? '' }}</textarea>
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

                {{-- FAQ BLOKLARI --}}
                @if($page->slug == 'faq')
                <div class="card shadow mb-4 border-left-success">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-question-circle me-2"></i> FAQ (Sual-Cavab)</h6>
                        <button type="button" class="btn btn-sm btn-success" onclick="addFaqItem()">
                            <i class="fas fa-plus"></i> Yeni Sual
                        </button>
                    </div>
                    <div class="card-body" id="faq-container">
                        @php $faqItems = $page->getMeta('faq_items', []); @endphp
                        @foreach($faqItems as $key => $item)
                            <div class="card mb-3 border faq-item" id="faq-{{ $key }}">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                    <span><strong>Sual #{{ $key + 1 }}</strong></span>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeElement('faq-{{ $key }}')"><i class="fas fa-trash"></i></button>
                                </div>
                                <div class="card-body">
                                    <ul class="nav nav-pills mb-2">
                                        @foreach($languages as $i => $l)
                                            <li class="nav-item">
                                                <button class="nav-link {{ $i === 0 ? 'active' : '' }} py-1 px-2 small" data-bs-toggle="pill" data-bs-target="#faq-{{ $key }}-{{ $l->code }}" type="button">{{ $l->code }}</button>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content">
                                        @foreach($languages as $i => $l)
                                            <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="faq-{{ $key }}-{{ $l->code }}">
                                                <input type="text" name="faq_items[{{ $key }}][question][{{ $l->code }}]" class="form-control mb-2" placeholder="Sual ({{ $l->name }})" value="{{ $item['question'][$l->code] ?? '' }}">
                                                <textarea name="faq_items[{{ $key }}][answer][{{ $l->code }}]" class="form-control" rows="2" placeholder="Cavab ({{ $l->name }})">{{ $item['answer'][$l->code] ?? '' }}</textarea>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- PRICING (QİYMƏTLƏR) - YENİ STRUKTUR --}}
                @if($page->slug == 'pricing')

                {{-- 1. İXTİSAS QİYMƏTLƏRİ --}}
                <div class="card shadow mb-4 border-left-success">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-list me-2"></i> İxtisas üzrə Qiymətlər</h6>
                        <button type="button" class="btn btn-sm btn-success" onclick="addSpecialty()">
                            <i class="fas fa-plus"></i> Yeni İxtisas
                        </button>
                    </div>
                    <div class="card-body" id="specialty-container">
                        @php $specialties = $page->getMeta('specialties_list', []); @endphp
                        @foreach($specialties as $key => $item)
                            <div class="card mb-2 border specialty-item" id="spec-{{ $key }}">
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="flex-grow-1">
                                            <ul class="nav nav-pills mb-1" style="font-size: 0.7rem;">
                                                @foreach($languages as $i => $l)
                                                    <li class="nav-item"><a class="nav-link {{ $i===0?'active':'' }} py-0 px-2" data-bs-toggle="pill" href="#spec-{{ $key }}-{{ $l->code }}">{{ $l->code }}</a></li>
                                                @endforeach
                                            </ul>
                                            <div class="tab-content">
                                                @foreach($languages as $i => $l)
                                                    <div class="tab-pane fade {{ $i===0?'show active':'' }}" id="spec-{{ $key }}-{{ $l->code }}">
                                                        <input type="text" name="specialties_list[{{ $key }}][name][{{ $l->code }}]" class="form-control form-control-sm" placeholder="İxtisas adı ({{ $l->name }})" value="{{ $item['name'][$l->code] ?? '' }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div style="width: 150px;">
                                            <input type="text" name="specialties_list[{{ $key }}][price]" class="form-control form-control-sm" placeholder="Qiymət (AZN)" value="{{ $item['price'] ?? '' }}">
                                        </div>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeElement('spec-{{ $key }}')"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- 2. PAKETLƏR --}}
                <div class="card shadow mb-4 border-left-warning">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-box-open me-2"></i> Ümumi Paketlər</h6>
                        <button type="button" class="btn btn-sm btn-warning text-dark" onclick="addPackage()">
                            <i class="fas fa-plus"></i> Yeni Paket
                        </button>
                    </div>
                    <div class="card-body" id="packages-container">
                        @php $packages = $page->getMeta('packages_list', []); @endphp
                        @foreach($packages as $key => $item)
                            <div class="card mb-3 border package-item" id="pack-{{ $key }}">
                                <div class="card-header bg-light py-1 d-flex justify-content-between align-items-center">
                                    <small class="fw-bold">Paket #{{ $key + 1 }}</small>
                                    <button type="button" class="btn btn-sm btn-danger py-0" onclick="removeElement('pack-{{ $key }}')"><i class="fas fa-times"></i></button>
                                </div>
                                <div class="card-body p-2">
                                    <div class="mb-2">
                                        <label class="small fw-bold">Qiymət (AZN)</label>
                                        <input type="text" name="packages_list[{{ $key }}][price]" class="form-control form-control-sm" value="{{ $item['price'] ?? '' }}">
                                    </div>
                                    <ul class="nav nav-pills mb-2" style="font-size: 0.7rem;">
                                        @foreach($languages as $i => $l)
                                            <li class="nav-item"><a class="nav-link {{ $i===0?'active':'' }} py-0 px-2" data-bs-toggle="pill" href="#pack-{{ $key }}-{{ $l->code }}">{{ $l->code }}</a></li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content">
                                        @foreach($languages as $i => $l)
                                            <div class="tab-pane fade {{ $i===0?'show active':'' }}" id="pack-{{ $key }}-{{ $l->code }}">
                                                <input type="text" name="packages_list[{{ $key }}][title][{{ $l->code }}]" class="form-control form-control-sm mb-1" placeholder="Paket Adı" value="{{ $item['title'][$l->code] ?? '' }}">
                                                <textarea name="packages_list[{{ $key }}][description][{{ $l->code }}]" class="form-control form-control-sm" rows="2" placeholder="Açıqlama">{{ $item['description'][$l->code] ?? '' }}</textarea>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- 3. ALT HTML HİSSƏSİ --}}
                <div class="card shadow mb-4 border-left-secondary">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-secondary"><i class="fas fa-code me-2"></i> Alt Hissə (HTML/Əlaqə)</h6>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3">
                            @foreach($languages as $index => $lang)
                                <li class="nav-item">
                                    <button class="nav-link {{ $index == 0 ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#bottom-{{ $lang->code }}" type="button">
                                        {{ $lang->name }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach($languages as $index => $lang)
                                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="bottom-{{ $lang->code }}">
                                    <textarea class="form-control editor" name="bottom_html[{{ $lang->code }}]" rows="5">{{ $page->getMeta('bottom_html')[$lang->code] ?? '' }}</textarea>
                                    <div class="form-text small">Bura login məlumatları, xəritə və ya əlavə əlaqə vasitələri yaza bilərsiniz.</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @endif

                {{-- ANA SƏHİFƏ AYARLARI --}}
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

            {{-- SAĞ TƏRƏF (Yayımlama və SEO) --}}
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

@section('scripts')
{{-- CKEditor Script --}}
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    const languages = @json($languages);

    function removeElement(id) { document.getElementById(id).remove(); }

    // --- About Section Script ---
    let sectionCount = {{ isset($sections) ? count($sections) : 0 }};
    function addSection() {
        let html = `
            <div class="card mb-3 border section-item" id="section-${sectionCount}">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <span><strong>Yeni Blok</strong></span>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeElement('section-${sectionCount}')"><i class="fas fa-trash"></i></button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Şəkil</label>
                            <input type="file" name="sections[${sectionCount}][image]" class="form-control">
                        </div>
                        <div class="col-md-8">
                            <ul class="nav nav-pills mb-2">
                                ${languages.map((l, i) => `<li class="nav-item"><button class="nav-link ${i===0?'active':''} py-1 px-2 small" data-bs-toggle="pill" data-bs-target="#new-sec-${sectionCount}-${l.code}" type="button">${l.code}</button></li>`).join('')}
                            </ul>
                            <div class="tab-content">
                                ${languages.map((l, i) => `
                                    <div class="tab-pane fade ${i===0?'show active':''}" id="new-sec-${sectionCount}-${l.code}">
                                        <input type="text" name="sections[${sectionCount}][title][${l.code}]" class="form-control mb-2 form-control-sm" placeholder="Başlıq">
                                        <textarea name="sections[${sectionCount}][content][${l.code}]" class="form-control form-control-sm" rows="3" placeholder="Mətn"></textarea>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        document.getElementById('sections-container').insertAdjacentHTML('beforeend', html);
        sectionCount++;
    }

    // --- FAQ Script ---
    let faqCount = {{ isset($faqItems) ? count($faqItems) : 0 }};
    function addFaqItem() {
        let html = `
            <div class="card mb-3 border faq-item" id="faq-${faqCount}">
                <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                    <span><strong>Yeni Sual</strong></span>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeElement('faq-${faqCount}')"><i class="fas fa-trash"></i></button>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills mb-2">
                        ${languages.map((l, i) => `<li class="nav-item"><button class="nav-link ${i===0?'active':''} py-1 px-2 small" data-bs-toggle="pill" data-bs-target="#new-faq-${faqCount}-${l.code}" type="button">${l.code}</button></li>`).join('')}
                    </ul>
                    <div class="tab-content">
                        ${languages.map((l, i) => `
                            <div class="tab-pane fade ${i===0?'show active':''}" id="new-faq-${faqCount}-${l.code}">
                                <input type="text" name="faq_items[${faqCount}][question][${l.code}]" class="form-control mb-2" placeholder="Sual (${l.name})">
                                <textarea name="faq_items[${faqCount}][answer][${l.code}]" class="form-control" rows="2" placeholder="Cavab (${l.name})"></textarea>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>`;
        document.getElementById('faq-container').insertAdjacentHTML('beforeend', html);
        faqCount++;
    }

    // --- Pricing Scripts ---
    let specCount = {{ isset($specialties) ? count($specialties) : 0 }};
    function addSpecialty() {
        let html = `
            <div class="card mb-2 border specialty-item" id="spec-${specCount}">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="flex-grow-1">
                            <ul class="nav nav-pills mb-1" style="font-size: 0.7rem;">
                                ${languages.map((l, i) => `<li class="nav-item"><a class="nav-link ${i===0?'active':''} py-0 px-2" data-bs-toggle="pill" href="#spec-${specCount}-${l.code}">${l.code}</a></li>`).join('')}
                            </ul>
                            <div class="tab-content">
                                ${languages.map((l, i) => `
                                    <div class="tab-pane fade ${i===0?'show active':''}" id="spec-${specCount}-${l.code}">
                                        <input type="text" name="specialties_list[${specCount}][name][${l.code}]" class="form-control form-control-sm" placeholder="İxtisas adı (${l.name})">
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        <div style="width: 150px;">
                            <input type="text" name="specialties_list[${specCount}][price]" class="form-control form-control-sm" placeholder="Qiymət">
                        </div>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeElement('spec-${specCount}')"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>`;
        document.getElementById('specialty-container').insertAdjacentHTML('beforeend', html);
        specCount++;
    }

    let packCount = {{ isset($packages) ? count($packages) : 0 }};
    function addPackage() {
        let html = `
            <div class="card mb-3 border package-item" id="pack-${packCount}">
                <div class="card-header bg-light py-1 d-flex justify-content-between align-items-center">
                    <small class="fw-bold">Yeni Paket</small>
                    <button type="button" class="btn btn-sm btn-danger py-0" onclick="removeElement('pack-${packCount}')"><i class="fas fa-times"></i></button>
                </div>
                <div class="card-body p-2">
                    <div class="mb-2">
                        <label class="small fw-bold">Qiymət (AZN)</label>
                        <input type="text" name="packages_list[${packCount}][price]" class="form-control form-control-sm">
                    </div>
                    <ul class="nav nav-pills mb-2" style="font-size: 0.7rem;">
                        ${languages.map((l, i) => `<li class="nav-item"><a class="nav-link ${i===0?'active':''} py-0 px-2" data-bs-toggle="pill" href="#pack-${packCount}-${l.code}">${l.code}</a></li>`).join('')}
                    </ul>
                    <div class="tab-content">
                        ${languages.map((l, i) => `
                            <div class="tab-pane fade ${i===0?'show active':''}" id="pack-${packCount}-${l.code}">
                                <input type="text" name="packages_list[${packCount}][title][${l.code}]" class="form-control form-control-sm mb-1" placeholder="Paket Adı">
                                <textarea name="packages_list[${packCount}][description][${l.code}]" class="form-control form-control-sm" rows="2" placeholder="Açıqlama"></textarea>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>`;
        document.getElementById('packages-container').insertAdjacentHTML('beforeend', html);
        packCount++;
    }

    // CKEditor Initialization for Bottom HTML and Main Content
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.editor').forEach(e => {
            ClassicEditor.create(e).catch(error => console.error(error));
        });
    });
</script>
@endsection

@endsection
