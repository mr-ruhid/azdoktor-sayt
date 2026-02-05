<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">İstifadəçi Məlumatları</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Ad Soyad <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name"
                           value="{{ $user->name ?? old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">E-poçt <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email"
                           value="{{ $user->email ?? old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Şifrə
                        @if(isset($user)) <span class="text-muted small">(Boş qalsa dəyişməyəcək)</span> @else <span class="text-danger">*</span> @endif
                    </label>
                    <input type="password" class="form-control" name="password"
                           {{ isset($user) ? '' : 'required' }} minlength="8">
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Rol və İcazələr</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Rol Seçin <span class="text-danger">*</span></label>
                    <select class="form-select" name="role" required>
                        <option value="">Seçin...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}"
                                {{ (isset($user) && $user->hasRole($role->name)) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Yadda Saxla
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
