@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Abunəçilər</h3>
        <button class="btn btn-secondary btn-sm disabled">
            Cəmi: {{ $subscribers->total() }}
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">Email Siyahısı</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Email Adresi</th>
                            <th>Abunə Tarixi</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscribers as $sub)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <a href="mailto:{{ $sub->email }}" class="fw-bold text-decoration-none">
                                    {{ $sub->email }}
                                </a>
                            </td>
                            <td>{{ $sub->created_at->format('d.m.Y H:i') }}</td>
                            <td class="text-center">
                                @if($sub->is_active)
                                    <span class="badge bg-success">Aktiv</span>
                                @else
                                    <span class="badge bg-secondary">Deaktiv</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <form action="{{ route('admin.subscribers.destroy', $sub->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu abunəçini silmək istədiyinizə əminsiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Sil">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-envelope-open fa-3x mb-3 text-gray-300"></i>
                                <p class="mb-0">Hələ heç bir abunəçi yoxdur.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $subscribers->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
