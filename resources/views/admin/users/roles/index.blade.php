@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Rollar və İcazələr</h3>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Yeni Rol Yarat
        </a>
    </div>

    <!-- Bildirişlər -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white border-bottom-0">
            <h6 class="m-0 font-weight-bold text-primary">Mövcud Rollar</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="rolesTable">
                    <thead class="table-light">
                        <tr>
                            <th class="border-top-0" style="width: 50px;">#</th>
                            <th class="border-top-0">Rol Adı</th>
                            <th class="border-top-0">İcazə Sayı</th>
                            <th class="border-top-0 text-end pe-4">Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                        <tr>
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle">
                                <span class="badge bg-primary fs-6 px-3 py-2">{{ $role->name }}</span>
                            </td>
                            <td class="align-middle">
                                <span class="text-muted small">
                                    <i class="fas fa-key me-1"></i> {{ $role->permissions->count() }} İcazə
                                </span>
                            </td>
                            <td class="align-middle text-end pe-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-outline-primary" title="Düzəliş Et">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if($role->name !== 'Super Admin')
                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu rolu və ona aid bütün səlahiyyətləri silmək istədiyinizə əminsiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger border-start-0" onclick="if(confirm('Silmək istədiyinizə əminsiniz?')) this.form.submit();" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 text-gray-300"></i>
                                <p>Hələ heç bir rol yaradılmayıb.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
