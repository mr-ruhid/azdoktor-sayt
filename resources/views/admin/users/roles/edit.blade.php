@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Rolu Redaktə Et: <span class="text-primary">{{ $role->name }}</span></h3>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Geri Qayıt
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Rol Məlumatları</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Rolun Adı</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $role->name) }}" required>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">İcazələr (Səlahiyyətlər)</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" id="selectAll">Hamsını Seç</button>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($permissions as $groupName => $perms)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-left-primary shadow-sm">
                                <div class="card-header bg-light py-2">
                                    <div class="form-check">
                                        <!-- Qrup checkbox-ı -->
                                        <input class="form-check-input group-checkbox" type="checkbox" id="group_{{ $groupName }}" data-group="{{ $groupName }}">
                                        <label class="form-check-label fw-bold text-uppercase" for="group_{{ $groupName }}">
                                            {{ ucfirst($groupName) }} Bölməsi
                                        </label>
                                    </div>
                                </div>
                                <div class="card-body py-2">
                                    @foreach($perms as $permission)
                                        <div class="form-check mb-1">
                                            <!-- İcazə checkbox-ı: Əgər rolda varsa 'checked' olsun -->
                                            <input class="form-check-input permission-checkbox group-{{ $groupName }}"
                                                   type="checkbox"
                                                   name="permissions[]"
                                                   value="{{ $permission->name }}"
                                                   id="perm_{{ $permission->id }}"
                                                   {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="perm_{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer bg-white">
                <button type="submit" class="btn btn-primary icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-save"></i>
                    </span>
                    <span class="text">Yadda Saxla</span>
                </button>
            </div>
        </div>

    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // "Hamsını Seç" düyməsi
        document.getElementById('selectAll').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.permission-checkbox');
            let allChecked = Array.from(checkboxes).every(c => c.checked);

            checkboxes.forEach(c => c.checked = !allChecked);
            document.querySelectorAll('.group-checkbox').forEach(c => c.checked = !allChecked);

            this.textContent = allChecked ? "Hamsını Seç" : "Seçimi Ləğv Et";
        });

        // Qrup üzrə seçmə məntiqi
        document.querySelectorAll('.group-checkbox').forEach(groupCb => {
            // Səhifə yüklənəndə: Əgər qrupdakı bütün icazələr seçilibsə, qrup checkbox-ını da seçili et
            let groupName = groupCb.getAttribute('data-group');
            let groupPermissions = document.querySelectorAll('.permission-checkbox.group-' + groupName);
            let allGroupChecked = Array.from(groupPermissions).every(c => c.checked);
            if(allGroupChecked && groupPermissions.length > 0) {
                groupCb.checked = true;
            }

            // Dəyişiklik olanda
            groupCb.addEventListener('change', function() {
                let groupName = this.getAttribute('data-group');
                document.querySelectorAll('.group-' + groupName).forEach(cb => {
                    cb.checked = this.checked;
                });
            });
        });
    });
</script>
@endsection
