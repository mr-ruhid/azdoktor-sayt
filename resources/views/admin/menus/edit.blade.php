@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Redaktə Et</h1>
        <a href="{{ route('admin.menus.index', ['type' => $menu->type]) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri
        </a>
    </div>

    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="type" value="{{ $menu->type }}">

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Menyu Başlığı</h6>
                    </div>
                    <div class="card-body">
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

                        <div class="tab-content" id="langTabContent">
                            @foreach($languages as $index => $lang)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="{{ $lang->code }}" role="tabpanel">
                                    <div class="mb-3">
                                        <label class="form-label">Başlıq ({{ $lang->name }})</label>
                                        <input type="text" name="title[{{ $lang->code }}]"
                                               class="form-control"
                                               value="{{ $menu->getTranslation('title', $lang->code) }}"
                                               required>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                         {{-- AVTOMATİK URL SEÇİMİ --}}
                         <div class="alert alert-info mt-4">
                            <label class="form-label fw-bold">Mövcud Səhifələrdən Seç:</label>
                            <select class="form-select" id="routeSelect" onchange="fillUrl(this)">
                                <option value="">-- Siyahıdan seçin (Cari: {{ $menu->url }}) --</option>
                                @foreach($routes as $url => $label)
                                    <option value="{{ $url }}" {{ $menu->url == $url ? 'selected' : '' }}>
                                        {{ $label }} (URL: {{ $url }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">URL</label>
                            <input type="text" name="url" id="urlInput" class="form-control" value="{{ $menu->url }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Ayarlar</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">İkon</label>
                            <input type="text" name="icon" class="form-control" value="{{ $menu->icon }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Valideyn</label>
                            <select name="parent_id" class="form-select">
                                <option value="">-- Yoxdur --</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}" {{ $menu->parent_id == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->getTranslation('title', app()->getLocale()) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kimlər görə bilər?</label>
                            <select name="role" class="form-select">
                                <option value="all" {{ $menu->role == 'all' ? 'selected' : '' }}>Hamı</option>
                                <option value="guest" {{ $menu->role == 'guest' ? 'selected' : '' }}>Qonaqlar</option>
                                <option value="auth_user" {{ $menu->role == 'auth_user' ? 'selected' : '' }}>İstifadəçilər</option>
                                <option value="doctor" {{ $menu->role == 'doctor' ? 'selected' : '' }}>Həkimlər</option>
                            </select>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="status" id="status" {{ $menu->status ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Aktivdir</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Yenilə</button>
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
