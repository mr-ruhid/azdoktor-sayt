<div class="row">
    <div class="col-lg-9">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Səhifə Məzmunu (Tərcümə)</h6>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs mb-3" role="tablist">
                    @foreach($languages as $index => $lang)
                        <li class="nav-item">
                            <button class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#content-{{ $lang->code }}" type="button">
                                {{ $lang->name }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach($languages as $index => $lang)
                        <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="content-{{ $lang->code }}">
                            <div class="mb-3">
                                <label class="form-label">Başlıq ({{ $lang->code }}) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title[{{ $lang->code }}]"
                                       value="{{ $page ? $page->getTranslation('title', $lang->code, false) : '' }}"
                                       {{ $lang->is_default ? 'required' : '' }}>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Məzmun ({{ $lang->code }})</label>
                                <textarea class="form-control editor" name="content[{{ $lang->code }}]" rows="10">{{ $page ? $page->getTranslation('content', $lang->code, false) : '' }}</textarea>
                            </div>

                            <hr>
                            <h6 class="text-info"><i class="fas fa-search"></i> SEO ({{ $lang->code }})</h6>
                            <div class="mb-2">
                                <input type="text" class="form-control form-control-sm" name="seo_title[{{ $lang->code }}]" placeholder="Meta Title" value="{{ $page ? $page->getTranslation('seo_title', $lang->code, false) : '' }}">
                            </div>
                            <div class="mb-2">
                                <textarea class="form-control form-control-sm" name="seo_description[{{ $lang->code }}]" rows="2" placeholder="Meta Description">{{ $page ? $page->getTranslation('seo_description', $lang->code, false) : '' }}</textarea>
                            </div>
                            <div>
                                <input type="text" class="form-control form-control-sm" name="seo_keywords[{{ $lang->code }}]" placeholder="Keywords" value="{{ $page ? $page->getTranslation('seo_keywords', $lang->code, false) : '' }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Ayarlar</h6>
            </div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="status" value="1" {{ ($page->status ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label">Status (Aktiv)</label>
                </div>

                <div class="mb-3 text-center">
                    <label class="form-label d-block text-start">Qapaq Şəkli</label>
                    @if($page && $page->image)
                        <img src="{{ asset($page->image) }}" class="img-fluid rounded mb-2 border" style="max-height: 150px;">
                    @endif
                    <input type="file" class="form-control form-control-sm" name="image">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Yadda Saxla
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
