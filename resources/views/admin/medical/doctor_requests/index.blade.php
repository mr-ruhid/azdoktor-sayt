@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Həkim İstəkləri (Anketlər)</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white border-bottom-0">
            <h6 class="m-0 font-weight-bold text-primary">Daxil Olan Müraciətlər</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Ad Soyad / Yaş</th>
                            <th>İxtisas / Vəzifə</th>
                            <th>Əlaqə Məlumatları</th>
                            <th>CV Faylı</th>
                            <th>Tarix</th>
                            <th>Status</th>
                            <th class="text-end px-4">Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr class="{{ $req->status == 'new' ? 'table-primary bg-opacity-10' : '' }}">
                            <td class="px-3">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-bold">{{ $req->full_name }}</div>
                                <small class="text-muted">{{ $req->age ? $req->age . ' yaş' : 'Qeyd olunmayıb' }}</small>
                            </td>
                            <td>
                                <div class="text-dark">{{ $req->specialty ?? '-' }}</div>
                                <small class="text-muted">{{ $req->position ?? '-' }} / {{ $req->clinic ?? '-' }}</small>
                            </td>
                            <td>
                                <div><i class="fas fa-phone-alt text-muted me-1 small"></i> {{ $req->phone }}</div>
                                <div class="small text-muted"><i class="fas fa-envelope me-1 small"></i> {{ $req->email }}</div>
                                @if($req->contact_method)
                                    <span class="badge bg-light text-dark border mt-1">
                                        <i class="fas fa-comment-dots me-1"></i> {{ $req->contact_method }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($req->cv_file)
                                    <a href="{{ asset($req->cv_file) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-file-pdf me-1"></i> CV Bax
                                    </a>
                                @else
                                    <span class="text-muted small">CV Yoxdur</span>
                                @endif
                            </td>
                            <td>
                                <div class="small">{{ $req->created_at->format('d.m.Y') }}</div>
                                <div class="small text-muted">{{ $req->created_at->format('H:i') }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $req->status_badge }}">{{ $req->status_label }}</span>
                            </td>
                            <td class="text-end px-4">
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Status
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <form action="{{ route('admin.doctor_requests.status', $req->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="contacted">
                                                <button class="dropdown-item"><i class="fas fa-check text-success me-2"></i> Əlaqə Saxlanıldı</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.doctor_requests.status', $req->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="viewed">
                                                <button class="dropdown-item"><i class="fas fa-eye text-info me-2"></i> Baxıldı</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.doctor_requests.status', $req->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="rejected">
                                                <button class="dropdown-item text-danger"><i class="fas fa-times me-2"></i> İmtina</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>

                                <form action="{{ route('admin.doctor_requests.destroy', $req->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu müraciəti silmək istədiyinizə əminsiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger ms-1" title="Sil"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 text-gray-300"></i>
                                <p>Hələ heç bir müraciət daxil olmayıb.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $requests->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
