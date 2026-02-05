<div class="row">
    <!-- Sol tərəf: Tərcümə -->
    <div class="col-md-7">
        <h6 class="border-bottom pb-2 mb-3 text-primary">Tərcümə Ediləcək Məlumatlar</h6>

        <ul class="nav nav-pills mb-3" role="tablist">
            @foreach($languages as $index => $lang)
                <li class="nav-item">
                    <button class="nav-link lang-tab-btn {{ $index == 0 ? 'active' : '' }}"
                            data-bs-toggle="tab"
                            data-bs-target="#content-{{ $lang->code }}-{{ $clinic ? $clinic->id : 'new' }}"
                            type="button">
                        {{ $lang->name }}
                    </button>
                </li>
            @endforeach
        </ul>

        <div class="tab-content">
            @foreach($languages as $index => $lang)
                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="content-{{ $lang->code }}-{{ $clinic ? $clinic->id : 'new' }}">
                    <div class="mb-3">
                        <label class="form-label">Klinikanın Adı ({{ $lang->code }}) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name[{{ $lang->code }}]"
                               value="{{ $clinic ? $clinic->getTranslation('name', $lang->code, false) : '' }}"
                               {{ $lang->is_default ? 'required' : '' }}>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ünvan ({{ $lang->code }})</label>
                        <input type="text" class="form-control" name="address[{{ $lang->code }}]"
                               value="{{ $clinic ? $clinic->getTranslation('address', $lang->code, false) : '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Haqqında ({{ $lang->code }})</label>
                        <textarea class="form-control" name="description[{{ $lang->code }}]" rows="4">{{ $clinic ? $clinic->getTranslation('description', $lang->code, false) : '' }}</textarea>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Sağ tərəf: Əsas Məlumatlar -->
    <div class="col-md-5">
        <h6 class="border-bottom pb-2 mb-3 text-primary">Əsas Məlumatlar & Xəritə</h6>

        <div class="mb-3">
            <label class="form-label">Telefon <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="phone" value="{{ $clinic->phone ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{ $clinic->email ?? '' }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Loqo</label>
            <input type="file" class="form-control" name="image">
            @if(isset($clinic) && $clinic->image)
                <div class="mt-2">
                    <img src="{{ asset($clinic->image) }}" width="80" class="rounded border">
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status">
                <option value="1" {{ (isset($clinic) && $clinic->status) ? 'selected' : '' }}>Aktiv</option>
                <option value="0" {{ (isset($clinic) && !$clinic->status) ? 'selected' : '' }}>Passiv</option>
            </select>
        </div>

        <h6 class="border-bottom pb-2 mt-4 mb-3 text-primary">Klinikanın Yeri</h6>
        <div class="row">
            <div class="col-6 mb-2">
                <input type="text" class="form-control form-control-sm" name="latitude" placeholder="Lat" value="{{ $clinic->latitude ?? '' }}">
            </div>
            <div class="col-6 mb-2">
                <input type="text" class="form-control form-control-sm" name="longitude" placeholder="Lng" value="{{ $clinic->longitude ?? '' }}">
            </div>
        </div>

        <!-- Map Container (Create və Edit üçün fərqli ID lazımdır, burada sadələşdirdim, realda unique ID verin) -->
        <div id="{{ isset($clinic) ? 'editMap'.$clinic->id : 'createMap' }}" style="height: 250px; width: 100%; border: 1px solid #ddd; border-radius: 4px; background: #eee;">
            @if(empty($yandexApiKey))
                <div class="d-flex align-items-center justify-content-center h-100 text-muted small">
                    API Açar tapılmadı
                </div>
            @endif
        </div>
    </div>
</div>
