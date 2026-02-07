@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Tənzimlə: {{ $sidebar->name }}</h3>
        <a href="{{ route('admin.sidebars.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Geri
        </a>
    </div>

    <form action="{{ route('admin.sidebars.update', $sidebar->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <!-- Ümumi Ayarlar -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-white">
                        <h6 class="m-0 font-weight-bold text-primary">Görünüş Ayarları</h6>
                    </div>
                    <div class="card-body">

                        <!-- Rənglər -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Arxa Fon Rəngi</label>
                                <input type="color" class="form-control form-control-color w-100" name="settings[background_color]"
                                       value="{{ $sidebar->settings['background_color'] ?? '#ffffff' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Yazı Rəngi</label>
                                <input type="color" class="form-control form-control-color w-100" name="settings[text_color]"
                                       value="{{ $sidebar->settings['text_color'] ?? '#333333' }}">
                            </div>
                        </div>

                        <!-- PC Sidebar Xüsusi Ayarları -->
                        @if($sidebar->type == 'pc_sidebar')
                            <div class="mb-3">
                                <label class="form-label">Genişlik (Width)</label>
                                <input type="text" class="form-control" name="settings[width]"
                                       value="{{ $sidebar->settings['width'] ?? '280px' }}" placeholder="280px">
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="settings[show_language_switcher]" value="1"
                                       {{ ($sidebar->settings['show_language_switcher'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label">Dil Dəyişdirici Görünsün</label>
                            </div>
                        @endif

                        <!-- Mobil Navbar Xüsusi Ayarları -->
                        @if($sidebar->type == 'mobile_navbar')
                            <div class="alert alert-info small">
                                <i class="fas fa-info-circle me-1"></i> Mobil navbarda axtarış mərkəzdə, menyu ikonları isə kənarlarda olur.
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="settings[show_search]" value="1"
                                       {{ ($sidebar->settings['show_search'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label">Axtarış Paneli (Mərkəzdə) Görünsün</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="settings[sticky]" value="1"
                                       {{ ($sidebar->settings['sticky'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label">Yuxarıdan Yapışsın (Sticky)</label>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Logo və Status -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-white">
                        <h6 class="m-0 font-weight-bold text-primary">Logo və Status</h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <label class="form-label d-block text-start">Panel Logosu</label>
                            @if($sidebar->logo)
                                <img src="{{ asset($sidebar->logo) }}" class="img-fluid border p-2 rounded mb-2" style="max-height: 80px; background: #eee;">
                            @else
                                <div class="p-3 bg-light border border-dashed rounded text-muted mb-2">Logo Yoxdur</div>
                            @endif
                            <input type="file" class="form-control form-control-sm" name="logo">
                        </div>

                        <hr>

                        <div class="form-check form-switch mb-3 text-start">
                            <input class="form-check-input" type="checkbox" name="status" value="1" {{ $sidebar->status ? 'checked' : '' }}>
                            <label class="form-check-label">Status (Aktiv)</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Yadda Saxla
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
