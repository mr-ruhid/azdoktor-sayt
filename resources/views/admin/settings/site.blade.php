@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Sayt Tənzimləmələri</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Sol Tərəf: Logolar və Ümumi -->
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-white">
                        <h6 class="m-0 font-weight-bold text-primary">Logolar & Görünüş</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Saytın Adı</label>
                            <input type="text" name="site_name" class="form-control" value="{{ $setting->site_name }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Əsas Logo</label>
                            <input type="file" name="logo" class="form-control mb-2" onchange="previewImage(this, 'preview_logo')">
                            @if($setting->logo)
                                <img src="{{ asset($setting->logo) }}" id="preview_logo" class="img-fluid border p-2 rounded" style="max-height: 80px; background: #f8f9fa;">
                            @else
                                <img id="preview_logo" style="max-height: 80px; display: none;">
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Logo (Gecə Rejimi)</label>
                            <input type="file" name="logo_dark" class="form-control mb-2" onchange="previewImage(this, 'preview_logo_dark')">
                            <div class="bg-dark p-2 rounded text-center">
                                @if($setting->logo_dark)
                                    <img src="{{ asset($setting->logo_dark) }}" id="preview_logo_dark" class="img-fluid" style="max-height: 80px;">
                                @else
                                    <img id="preview_logo_dark" style="max-height: 80px; display: none;">
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Favicon (Tab İkonu)</label>
                            <input type="file" name="favicon" class="form-control mb-2" onchange="previewImage(this, 'preview_favicon')">
                            @if($setting->favicon)
                                <img src="{{ asset($setting->favicon) }}" id="preview_favicon" class="img-fluid border p-2 rounded" style="max-height: 50px;">
                            @else
                                <img id="preview_favicon" style="max-height: 50px; display: none;">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sağ Tərəf: Çoxdilli SEO -->
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-white">
                        <h6 class="m-0 font-weight-bold text-primary">SEO Tənzimləmələri (Çoxdilli)</h6>
                    </div>
                    <div class="card-body">

                        <!-- Dil Tabları -->
                        <ul class="nav nav-tabs mb-3" id="seoTabs" role="tablist">
                            @foreach($languages as $index => $lang)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                            id="tab-{{ $lang->code }}"
                                            data-bs-toggle="tab"
                                            data-bs-target="#content-{{ $lang->code }}"
                                            type="button" role="tab">
                                        <i class="fas fa-flag"></i> {{ $lang->name }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Tab Məzmunu -->
                        <div class="tab-content" id="seoTabsContent">
                            @foreach($languages as $index => $lang)
                                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="content-{{ $lang->code }}" role="tabpanel">

                                    <div class="mb-3">
                                        <label class="form-label">Meta Title ({{ $lang->code }})</label>
                                        <input type="text"
                                               name="seo_title[{{ $lang->code }}]"
                                               class="form-control"
                                               value="{{ $setting->getTranslation('seo_title', $lang->code, false) }}"
                                               placeholder="Məs: AzDoktor - Onlayn Həkim Qəbulu">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Meta Description ({{ $lang->code }})</label>
                                        <textarea name="seo_description[{{ $lang->code }}]"
                                                  class="form-control"
                                                  rows="3"
                                                  placeholder="Sayt haqqında qısa məlumat...">{{ $setting->getTranslation('seo_description', $lang->code, false) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Meta Keywords ({{ $lang->code }})</label>
                                        <input type="text"
                                               name="seo_keywords[{{ $lang->code }}]"
                                               class="form-control"
                                               value="{{ $setting->getTranslation('seo_keywords', $lang->code, false) }}"
                                               placeholder="hekim, xestexana, onlayn qebul (vergüllə ayırın)">
                                    </div>

                                </div>
                            @endforeach
                        </div>

                    </div>
                    <div class="card-footer bg-white text-end">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-save"></i> Yadda Saxla
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById(previewId);
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
