@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Həkimlər</h3>
        <a href="{{ route('admin.doctors.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> Yeni Həkim
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <!-- Axtarış hissəsi -->
            <form action="{{ route('admin.doctors.index') }}" method="GET" class="row mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Həkim adı, ixtisas və ya klinika axtar..." value="{{ request('q') }}">
                        <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th width="70">Şəkil</th>
                            <th>Adı Soyadı</th>
                            <th>İxtisas</th>
                            <th>Klinika</th>
                            <th>Telefon</th>
                            <th>Status</th>
                            <th class="text-end">Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($doctors as $doctor)
                            <tr>
                                <td>{{ $loop->iteration + ($doctors->currentPage() - 1) * $doctors->perPage() }}</td>
                                <td>
                                    @if ($doctor->image)
                                        <img src="{{ asset($doctor->image) }}" class="rounded-circle border" width="50" height="50" style="object-fit: cover;">
                                    @else
                                        <div class="rounded-circle border bg-light d-flex align-items-center justify-content-center text-secondary" style="width: 50px; height: 50px;">
                                            <i class="fas fa-user-md fa-lg"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $doctor->first_name }} {{ $doctor->last_name }}</div>
                                    <small class="text-muted">{{ $doctor->email }}</small>
                                </td>
                                <td>
                                    @if($doctor->specialty)
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info">{{ $doctor->specialty->name }}</span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($doctor->clinic)
                                        {{ $doctor->clinic->name }}
                                    @else
                                        <span class="text-muted small">Təyin edilməyib</span>
                                    @endif
                                </td>
                                <td>{{ $doctor->phone }}</td>
                                <td>
                                    @if ($doctor->status)
                                        <span class="badge bg-success">Aktiv</span>
                                    @else
                                        <span class="badge bg-secondary">Deaktiv</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-sm btn-primary me-1" title="Düzəliş et">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu həkimi silmək istədiyinizə əminsiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-user-md fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0">Hələ heç bir həkim əlavə edilməyib.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $doctors->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
