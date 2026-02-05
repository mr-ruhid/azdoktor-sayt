<div class="row">
    <!-- SOL TƏRƏF: Məlumatlar -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
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
                                <label class="form-label">Məhsul Adı ({{ $lang->code }}) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name[{{ $lang->code }}]"
                                       placeholder="Məs: Paracetamol 500mg"
                                       value="{{ $product ? $product->getTranslation('name', $lang->code, false) : old('name.'.$lang->code) }}"
                                       {{ $lang->is_default ? 'required' : '' }}>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Qısa Təsvir ({{ $lang->code }})</label>
                                <textarea class="form-control" name="short_description[{{ $lang->code }}]" rows="3">{{ $product ? $product->getTranslation('short_description', $lang->code, false) : old('short_description.'.$lang->code) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ətraflı Təsvir ({{ $lang->code }})</label>
                                <textarea class="form-control editor" name="description[{{ $lang->code }}]" rows="10">{{ $product ? $product->getTranslation('description', $lang->code, false) : old('description.'.$lang->code) }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Qiymət və Stok Paneli -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Qiymət və Stok</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Qiymət (AZN) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="price" value="{{ $product->price ?? old('price') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Endirimli Qiymət (AZN)</label>
                        <input type="number" step="0.01" class="form-control" name="sale_price" value="{{ $product->sale_price ?? old('sale_price') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">SKU (Kod)</label>
                        <input type="text" class="form-control" name="sku" value="{{ $product->sku ?? old('sku') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok Miqdarı</label>
                        <input type="number" class="form-control" name="stock_quantity" value="{{ $product->stock_quantity ?? 0 }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SAĞ TƏRƏF: Ayarlar -->
    <div class="col-lg-4">
        <!-- Yayımla -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Yayımla</h6>
            </div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="status" value="1" {{ ($product->status ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label">Status (Aktiv)</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ ($product->is_featured ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label">Vitrin Məhsulu</label>
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
                        <option value="{{ $cat->id }}" {{ ($product->category_id ?? old('category_id')) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
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
                            {{ ($product && $product->tags->contains($tag->id)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="tag{{ $tag->id }}">
                            {{ $tag->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Əsas Şəkil -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Əsas Şəkil</h6>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    @if($product && $product->image)
                        <img src="{{ asset($product->image) }}" class="img-fluid rounded mb-2 border" id="preview-image" style="max-height: 150px;">
                    @else
                        <img src="" class="img-fluid rounded mb-2 border" id="preview-image" style="max-height: 150px; display: none;">
                        <div class="p-3 bg-light border border-dashed rounded text-muted" id="placeholder-image">
                            <i class="fas fa-image fa-2x"></i><br>Şəkil yoxdur
                        </div>
                    @endif
                </div>
                <input type="file" class="form-control form-control-sm" name="image" onchange="previewFile(this, 'preview-image', 'placeholder-image')">
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
