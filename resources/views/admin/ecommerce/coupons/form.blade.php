<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Kupon Məlumatları</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kupon Kodu <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" name="code"
                               placeholder="Məs: YAZ2024"
                               value="{{ $coupon->code ?? old('code') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Endirim Tipi</label>
                        <select class="form-select" name="type">
                            <option value="percent" {{ ($coupon->type ?? old('type')) == 'percent' ? 'selected' : '' }}>Faiz (%)</option>
                            <option value="fixed" {{ ($coupon->type ?? old('type')) == 'fixed' ? 'selected' : '' }}>Sabit Məbləğ (AZN)</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Endirim Dəyəri <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="value"
                               value="{{ $coupon->value ?? old('value') }}" required>
                        <div class="form-text">Məsələn: 10 (10% və ya 10 AZN)</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Minimum Səbət Məbləği</label>
                        <input type="number" step="0.01" class="form-control" name="min_spend"
                               value="{{ $coupon->min_spend ?? old('min_spend') }}">
                        <div class="form-text">Boş qalsa limit yoxdur.</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Başlama Tarixi</label>
                        <input type="date" class="form-control" name="start_date"
                               value="{{ isset($coupon->start_date) ? $coupon->start_date->format('Y-m-d') : old('start_date') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Bitmə Tarixi</label>
                        <input type="date" class="form-control" name="end_date"
                               value="{{ isset($coupon->end_date) ? $coupon->end_date->format('Y-m-d') : old('end_date') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">İstifadə Limiti (Say)</label>
                    <input type="number" class="form-control" name="usage_limit"
                           placeholder="Limitsiz üçün boş qoyun"
                           value="{{ $coupon->usage_limit ?? old('usage_limit') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Status</h6>
            </div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="status" value="1" {{ ($coupon->status ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label">Aktiv</label>
                </div>

                @if(isset($coupon))
                <hr>
                <div class="d-flex justify-content-between">
                    <span>İstifadə edilib:</span>
                    <span class="fw-bold">{{ $coupon->used_count }} dəfə</span>
                </div>
                @endif

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Yadda Saxla
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
