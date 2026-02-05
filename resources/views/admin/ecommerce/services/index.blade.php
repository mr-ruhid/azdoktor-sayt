@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Xidmətlər</h3>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Yeni Xidmət
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="50">#</th>
                            <th width="80">Şəkil</th>
                            <th>Xidmət Adı</th>
                            <th>Qiymət</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                @if($service->image)
                                    <img src="{{ asset($service->image) }}" class="rounded border" width="60" height="40" style="object-fit: cover;">
                                @else
                                    <div class="bg-light rounded border d-flex align-items-center justify-content-center" style="width: 60px; height: 40px;">
                                        <i class="fas fa-briefcase text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $service->name }}</div>
                                <div class="small text-muted">{{ Str::limit($service->short_description, 50) }}</div>
                            </td>
                            <td>
                                @if($service->price)
                                    <span class="fw-bold text-success">{{ $service->price }} ₼</span>
                                @else
                                    <span class="text-muted small">Razılaşma</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($service->status)
                                    <span class="badge bg-success">Aktiv</span>
                                @else
                                    <span class="badge bg-secondary">Deaktiv</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu xidməti silmək istədiyinizə əminsiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-start-0">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Hələ heç bir xidmət yoxdur.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $services->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
