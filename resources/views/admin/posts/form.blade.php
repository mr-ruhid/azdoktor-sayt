<div class="row">
    <!-- SOL TƏRƏF: Məzmun -->
    <div class="col-lg-9">
        <div class="card shadow mb-4">
            <div class="card-body">
                <!-- Dil Tabları (Məzmun üçün) -->
                <ul class="nav nav-tabs mb-3" role="tablist">
                    @foreach($languages as $index => $lang)
                        <li class="nav-item">
                            <button class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#main-content-{{ $lang->code }}"
                                    type="button">
                                <img src="{{ asset($lang->flag) }}" width="20" class="me-1"> {{ $lang->name }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach($languages as $index => $lang)
                        <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="main-content-{{ $lang->code }}">

                            {{-- Məqalə Başlığı --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Başlıq ({{ $lang->code }}) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" name="title[{{ $lang->code }}]"
                                       placeholder="Məqalənin başlığı..."
                                       value="{{ $post ? $post->getTranslation('title', $lang->code, false) : old('title.'.$lang->code) }}"
                                       {{ $lang->is_default ? 'required' : '' }}>
                            </div>

                            {{-- Məqalə Məzmunu (CKEditor) --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Məzmun ({{ $lang->code }})</label>
                                <textarea class="form-control editor" id="editor-{{ $lang->code }}" name="content[{{ $lang->code }}]" rows="15">
                                    {{ $post ? $post->getTranslation('content', $lang->code, false) : old('content.'.$lang->code) }}
                                </textarea>
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
                <p class="small text-muted mb-3">Bu bölmə axtarış sistemləri (Google) və sosial media paylaşımları üçün vacibdir.</p>

                <!-- Dil Tabları (SEO üçün) -->
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
                                       placeholder="Məs: Ən yaxşı Kardioloqlar - AzDoktor"
                                       value="{{ $post ? $post->getTranslation('seo_title', $lang->code, false) : '' }}">
                                <div class="form-text small">Başlıq Google-da necə görünsün? (Maks: 60 simvol)</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description ({{ $lang->code }})</label>
                                <textarea class="form-control" name="seo_description[{{ $lang->code }}]" rows="3">{{ $post ? $post->getTranslation('seo_description', $lang->code, false) : '' }}</textarea>
                                <div class="form-text small">Qısa məzmun. (Maks: 160 simvol)</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Keywords ({{ $lang->code }})</label>
                                <input type="text" class="form-control" name="seo_keywords[{{ $lang->code }}]"
                                       placeholder="hekim, xestexana, saglamliq"
                                       value="{{ $post ? $post->getTranslation('seo_keywords', $lang->code, false) : '' }}">
                                <div class="form-text small">Açar sözləri vergüllə ayırın.</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- SAĞ TƏRƏF: Ayarlar və Şəkil -->
    <div class="col-lg-3">
        <!-- Yayımla -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Yayımla</h6>
            </div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="status" value="1" {{ ($post->status ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label">Status (Aktiv)</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ ($post->is_featured ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label">Seçilmiş (Manşet)</label>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Yadda Saxla
                    </button>
                </div>
            </div>
        </div>

        <!-- Kateqoriya -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Kateqoriya</h6>
            </div>
            <div class="card-body">
                <select class="form-select mb-3" name="category_id">
                    <option value="">Kateqoriya Seçin</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ ($post->category_id ?? old('category_id')) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->getTranslation('name', app()->getLocale()) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Teqlər -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Teqlər</h6>
            </div>
            <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                @foreach($tags as $tag)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag{{ $tag->id }}"
                            {{ ($post && $post->tags->contains($tag->id)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="tag{{ $tag->id }}">
                            {{ $tag->getTranslation('name', app()->getLocale()) }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Qapaq Şəkli -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Qapaq Şəkli</h6>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    @if($post && $post->image)
                        <img src="{{ asset($post->image) }}" class="img-fluid rounded mb-2 border" id="preview-cover" style="max-height: 150px;">
                    @else
                        <img src="" class="img-fluid rounded mb-2 border" id="preview-cover" style="max-height: 150px; display: none;">
                        <div class="p-3 bg-light border border-dashed rounded text-muted" id="placeholder-cover">
                            <i class="fas fa-image fa-2x"></i><br>Şəkil yoxdur
                        </div>
                    @endif
                </div>
                <input type="file" class="form-control form-control-sm" name="image" onchange="previewCover(this)">
                <div class="form-text small mt-2">Bu şəkil həm bloq qapağı, həm də SEO (paylaşım) şəkli olacaq.</div>
            </div>
        </div>
    </div>
</div>

{{-- CKEditor Script --}}
{{-- Bu hissəni admin.layout faylında @yield('scripts') olan yerə düşəcək --}}
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    // Şəkil önizləmə
    function previewCover(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-cover').src = e.target.result;
                document.getElementById('preview-cover').style.display = 'block';
                var placeholder = document.getElementById('placeholder-cover');
                if(placeholder) placeholder.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // CKEditor-u bütün dillər üçün başlat
    document.addEventListener("DOMContentLoaded", function() {
        const languages = @json($languages->pluck('code'));

        languages.forEach(lang => {
            const editorElement = document.querySelector(`#editor-${lang}`);
            if (editorElement) {
                ClassicEditor
                    .create(editorElement, {
                        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo'],
                        heading: {
                            options: [
                                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                                { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                            ]
                        },
                        link: {
                            // Linklər üçün default ayarlar
                            addTargetToExternalLinks: true
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        });
    });
</script>

<style>
    /* CKEditor hündürlüyünü tənzimləmək */
    .ck-editor__editable_inline {
        min-height: 400px;
    }
</style>
