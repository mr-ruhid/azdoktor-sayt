<div class="row">
    <!-- Sol Tərəf: Şəxsi Məlumatlar və Tərcümə -->
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Şəxsi Məlumatlar (Tərcümə)</h6>
            </div>
            <div class="card-body">
                <!-- Dil Tabları -->
                <ul class="nav nav-pills mb-3" role="tablist">
                    @foreach($languages as $index => $lang)
                        <li class="nav-item">
                            <button class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                    data-bs-toggle="tab"
                                    data-bs-target="#lang-{{ $lang->code }}"
                                    type="button">
                                {{ $lang->name }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach($languages as $index => $lang)
                        <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="lang-{{ $lang->code }}">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ad ({{ $lang->code }}) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="first_name[{{ $lang->code }}]"
                                           value="{{ $doctor ? $doctor->getTranslation('first_name', $lang->code, false) : old('first_name.'.$lang->code) }}"
                                           {{ $lang->is_default ? 'required' : '' }}>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Soyad ({{ $lang->code }}) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="last_name[{{ $lang->code }}]"
                                           value="{{ $doctor ? $doctor->getTranslation('last_name', $lang->code, false) : old('last_name.'.$lang->code) }}"
                                           {{ $lang->is_default ? 'required' : '' }}>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Haqqında / Bio ({{ $lang->code }})</label>
                                <textarea class="form-control" name="bio[{{ $lang->code }}]" rows="5">{{ $doctor ? $doctor->getTranslation('bio', $lang->code, false) : old('bio.'.$lang->code) }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- İş Məlumatları -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">İş Rejimi və Qəbul</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Qiymət Aralığı</label>
                        <input type="text" class="form-control" name="price_range" placeholder="Məs: 30-50 AZN" value="{{ $doctor->price_range ?? old('price_range') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">İş Günləri</label>
                        <input type="text" class="form-control" name="work_days" placeholder="Məs: 1-5 günlər" value="{{ $doctor->work_days ?? old('work_days') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Başlama Saatı</label>
                        <input type="time" class="form-control" name="work_hour_start" value="{{ $doctor->work_hour_start ?? old('work_hour_start') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Bitmə Saatı</label>
                        <input type="time" class="form-control" name="work_hour_end" value="{{ $doctor->work_hour_end ?? old('work_hour_end') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Növbə Tipi</label>
                        <select class="form-select" name="queue_type">
                            <option value="1" {{ ($doctor->queue_type ?? old('queue_type')) == 1 ? 'selected' : '' }}>Saatlı (Randevu)</option>
                            <option value="2" {{ ($doctor->queue_type ?? old('queue_type')) == 2 ? 'selected' : '' }}>Canlı Növbə</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Qəbul Tipi</label>
                        <select class="form-select" name="appointment_type">
                            <option value="1" {{ ($doctor->appointment_type ?? old('appointment_type')) == 1 ? 'selected' : '' }}>Saytdan Qeydiyyat</option>
                            <option value="2" {{ ($doctor->appointment_type ?? old('appointment_type')) == 2 ? 'selected' : '' }}>Əlaqə ilə</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sağ Tərəf: Əlaqə və Parametrlər -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Əsas Parametrlər</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Klinika <span class="text-danger">*</span></label>
                    <select class="form-select" name="clinic_id" required>
                        <option value="">Seçin...</option>
                        @foreach($clinics as $clinic)
                            <option value="{{ $clinic->id }}" {{ ($doctor->clinic_id ?? old('clinic_id')) == $clinic->id ? 'selected' : '' }}>
                                {{ $clinic->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">İxtisas <span class="text-danger">*</span></label>
                    <select class="form-select" name="specialty_id" required>
                        <option value="">Seçin...</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}" {{ ($doctor->specialty_id ?? old('specialty_id')) == $specialty->id ? 'selected' : '' }}>
                                {{ $specialty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Telefon</label>
                    <input type="text" class="form-control" name="phone" value="{{ $doctor->phone ?? old('phone') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ $doctor->email ?? old('email') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Şəkil</label>
                    <input type="file" class="form-control" name="image">
                    @if($doctor && $doctor->image)
                        <div class="mt-2 text-center">
                            <img src="{{ asset($doctor->image) }}" class="rounded img-thumbnail" style="max-height: 150px;">
                        </div>
                    @endif
                </div>

                <hr>

                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="status" value="1" {{ ($doctor->status ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label">Status (Aktiv)</label>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="accepts_reservations" value="1" {{ ($doctor->accepts_reservations ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label">Rezervasiya Qəbulu</label>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Sosial Media</h6>
            </div>
            <div class="card-body">
                <div class="input-group mb-2">
                    <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                    <input type="text" class="form-control" name="social_instagram" placeholder="Instagram" value="{{ $doctor->social_instagram ?? '' }}">
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text"><i class="fab fa-facebook"></i></span>
                    <input type="text" class="form-control" name="social_facebook" placeholder="Facebook" value="{{ $doctor->social_facebook ?? '' }}">
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                    <input type="text" class="form-control" name="social_youtube" placeholder="Youtube" value="{{ $doctor->social_youtube ?? '' }}">
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text"><i class="fab fa-tiktok"></i></span>
                    <input type="text" class="form-control" name="social_tiktok" placeholder="Tiktok" value="{{ $doctor->social_tiktok ?? '' }}">
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text"><i class="fab fa-linkedin"></i></span>
                    <input type="text" class="form-control" name="social_linkedin" placeholder="Linkedin" value="{{ $doctor->social_linkedin ?? '' }}">
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-globe"></i></span>
                    <input type="text" class="form-control" name="social_website" placeholder="Website" value="{{ $doctor->social_website ?? '' }}">
                </div>
            </div>
        </div>
    </div>
</div>
