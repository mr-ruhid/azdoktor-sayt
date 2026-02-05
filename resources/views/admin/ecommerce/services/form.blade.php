<div class="row">
    <!-- SOL TƏRƏF: Məzmun -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Xidmət Məlumatları (Tərcümə)</h6>
            </div>
            <div class="card-body">
                <!-- Dil Tabları -->
                <ul class="nav nav-tabs mb-3" role="tablist">
                    @foreach($languages as $index => $lang)
                        <li class="nav-item">
                            <button class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#main-content-{{ $lang->code }}"
                                    type="button">
                                {{ $lang->name }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach($languages as $index => $lang)
                        <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="main-content-{{ $lang->code }}">

                            <div class="mb-3">
                                <label class="form-label">Xidmət Adı ({{ $lang->code }}) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" name="name[{{ $lang->code }}]"
                                       placeholder="Məs: Ümumi Terapevtik Müayinə"
                                       value="{{ $service ? $service->getTranslation('name', $lang->code, false) : old('name.'.$lang->code) }}"
                                       {{ $lang->is_default ? 'required' : '' }}>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Qısa Təsvir ({{ $lang->code }})</label>
                                <textarea class="form-control" name="short_description[{{ $lang->code }}]" rows="3" placeholder="Ana səhifədə blokda görünən qısa mətn">{{ $service ? $service->getTranslation('short_description', $lang->code, false) : old('short_description.'.$lang->code) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">Daxil olanlar ({{ $lang->code }})</label>
                                <textarea class="form-control editor" name="features[{{ $lang->code }}]" rows="5" placeholder="Xidmətə nələr daxildir? (Siyahı şəklində)">{{ $service ? $service->getTranslation('features', $lang->code, false) : old('features.'.$lang->code) }}</textarea>
                                <div class="form-text small">Məsələn: Qan analizi, EKQ, Həkim konsultasiyası.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Detallı Məlumat ({{ $lang->code }})</label>
                                <textarea class="form-control editor" name="description[{{ $lang->code }}]" rows="8">{{ $service ? $service->getTranslation('description', $lang->code, false) : old('description.'.$lang->code) }}</textarea>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- SEO PANELİ -->
        <div class="card shadow mb-4 border-left-info">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-search me-1"></i> SEO Tənzimləmələri</h6>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills mb-3" role="tablist">
                    @foreach($languages as $index => $lang)
                        <li class="nav-item">
                            <button class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#seo-content-{{ $lang->code }}"
                                    type="button">
                                {{ $lang->name }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach($languages as $index => $lang)
                        <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="seo-content-{{ $lang->code }}">
                            <div class="mb-3">
                                <label class="form-label">Meta Title ({{ $lang->code }})</label>
                                <input type="text" class="form-control" name="seo_title[{{ $lang->code }}]"
                                       placeholder="{{ $service ? $service->getTranslation('name', $lang->code, false) : '' }}"
                                       value="{{ $service ? $service->getTranslation('seo_title', $lang->code, false) : '' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description ({{ $lang->code }})</label>
                                <textarea class="form-control" name="seo_description[{{ $lang->code }}]" rows="3">{{ $service ? $service->getTranslation('seo_description', $lang->code, false) : '' }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Keywords ({{ $lang->code }})</label>
                                <input type="text" class="form-control" name="seo_keywords[{{ $lang->code }}]"
                                       value="{{ $service ? $service->getTranslation('seo_keywords', $lang->code, false) : '' }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- SAĞ TƏRƏF: Qiymət və Media -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Yayımla</h6>
            </div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="status" value="1" {{ ($service->status ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label">Status (Aktiv)</label>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Yadda Saxla
                    </button>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Qiymət</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Xidmət Qiyməti (AZN)</label>
                    <div class="input-group">
                        <input type="number" step="0.01" class="form-control" name="price" value="{{ $service->price ?? old('price') }}">
                        <span class="input-group-text">₼</span>
                    </div>
                    <div class="form-text small">Qiymət razılaşma ilədirsə boş qoyun.</div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Qapaq Şəkli</h6>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    @if($service && $service->image)
                        <img src="{{ asset($service->image) }}" class="img-fluid rounded mb-2 border" id="preview-image" style="max-height: 150px;">
                    @else
                        <img src="" class="img-fluid rounded mb-2 border" id="preview-image" style="max-height: 150px; display: none;">
                        <div class="p-3 bg-light border border-dashed rounded text-muted" id="placeholder-image">
                            <i class="fas fa-image fa-2x"></i><br>Şəkil yoxdur
                        </div>
                    @endif
                </div>
                <input type="file" class="form-control form-control-sm" name="image" onchange="previewFile(this, 'preview-image', 'placeholder-image')">
                <div class="form-text small mt-2">Bu şəkil SEO paylaşımlarında da görünəcək.</div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewFile(input, previewId, placeholderId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
                document.getElementById(previewId).style.display = 'block';
                var placeholder = document.getElementById(placeholderId);
                if(placeholder) placeholder.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
