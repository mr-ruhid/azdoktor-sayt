@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Yeni Menyu ({{ $currentType == 'pc_sidebar' ? 'PC' : 'Mobil' }})</h1>
        <a href="{{ route('admin.menus.index', ['type' => $currentType]) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri
        </a>
    </div>

    <form action="{{ route('admin.menus.store') }}" method="POST">
        @csrf
        <input type="hidden" name="type" value="{{ $currentType }}">

        <div class="row">
            {{-- Sol Tərəf: Əsas Məlumatlar --}}
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Menyu Başlığı və Tərcümələr</h6>
                    </div>
                    <div class="card-body">
                        {{-- Dil Tabları --}}
                        <ul class="nav nav-tabs mb-3" id="langTab" role="tablist">
                            @foreach($languages as $index => $lang)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                            id="{{ $lang->code }}-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#{{ $lang->code }}"
                                            type="button" role="tab">
                                        <img src="{{ asset($lang->flag) }}" width="20" class="me-1"> {{ $lang->name }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Tab Məzmunu --}}
                        <div class="tab-content" id="langTabContent">
                            @foreach($languages as $index => $lang)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="{{ $lang->code }}" role="tabpanel">
                                    <div class="mb-3">
                                        <label class="form-label">Başlıq ({{ $lang->name }}) <span class="text-danger">*</span></label>
                                        <input type="text" name="title[{{ $lang->code }}]" class="form-control" required placeholder="Məs: Ana Səhifə">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- AVTOMATİK URL SEÇİMİ --}}
                        <div class="alert alert-info mt-4">
                            <label class="form-label fw-bold">Mövcud Səhifələrdən Seç:</label>
                            <select class="form-select" id="routeSelect" onchange="fillUrl(this)">
                                <option value="">-- Siyahıdan seçin və ya aşağıda əl ilə yazın --</option>
                                @foreach($routes as $url => $label)
                                    <option value="{{ $url }}">{{ $label }} (URL: {{ $url }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">URL / Link</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ url('/') }}/</span>
                                <input type="text" name="url" id="urlInput" class="form-control" placeholder="about">
                            </div>
                            <small class="text-muted">Seçim etdiyiniz zaman bura avtomatik dolacaq.</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sağ Tərəf: Ayarlar --}}
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Ayarlar</h6>
                    </div>
                    <div class="card-body">

                        {{-- İkon --}}
                        <div class="mb-3">
                            <label class="form-label">İkon (FontAwesome)</label>
                            <input type="text" name="icon" class="form-control" placeholder="fas fa-home">
                            <small><a href="https://fontawesome.com/v5/search" target="_blank">İkonlara buradan baxın</a></small>
                        </div>

                        {{-- Valideyn Menyu --}}
                        <div class="mb-3">
                            <label class="form-label">Valideyn Menyu</label>
                            <select name="parent_id" class="form-select">
                                <option value="">-- Yoxdur (Əsas Menyu) --</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}">
                                        {{ $parent->getTranslation('title', app()->getLocale()) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Görünürlük (Rol) --}}
                        <div class="mb-3">
                            <label class="form-label">Kimlər görə bilər?</label>
                            <select name="role" class="form-select">
                                <option value="all">Hamı</option>
                                <option value="guest">Yalnız Qonaqlar (Giriş etməyənlər)</option>
                                <option value="auth_user">Yalnız İstifadəçilər (User+Doctor)</option>
                                <option value="doctor">Yalnız Həkimlər</option>
                            </select>
                        </div>

                        {{-- Status --}}
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="status" id="status" checked>
                            <label class="form-check-label" for="status">Aktivdir</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Yadda Saxla</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@section('scripts')
<script>
    function fillUrl(select) {
        var urlInput = document.getElementById('urlInput');
        if (select.value) {
            urlInput.value = select.value;
        }
    }
</script>
@endsection

@endsection
