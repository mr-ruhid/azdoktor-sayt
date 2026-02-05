@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Rezervasiyalar</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">Qəbul Cədvəli</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Xəstə Adı / Əlaqə</th>
                            <th>Həkim / Klinika</th>
                            <th>Tarix & Saat</th>
                            <th>Status</th>
                            <th>Qeyd</th>
                            <th class="text-end px-4">Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $res)
                        <tr>
                            <td class="px-3">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-bold">{{ $res->name }}</div>
                                <div class="small text-muted"><i class="fas fa-phone me-1"></i> {{ $res->phone }}</div>
                            </td>
                            <td>
                                @if($res->doctor)
                                    <div class="text-dark font-weight-bold">{{ $res->doctor->full_name }}</div>
                                    <small class="text-muted">{{ $res->doctor->clinic->name ?? 'Klinika yoxdur' }}</small>
                                @else
                                    <span class="text-danger">Həkim silinib</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-primary">{{ $res->reservation_date->format('d.m.Y') }}</div>
                                <div class="small">{{ \Carbon\Carbon::parse($res->reservation_time)->format('H:i') }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $res->status_badge }}">{{ $res->status_label }}</span>
                            </td>
                            <td>
                                @if($res->note)
                                    <span class="d-inline-block text-truncate" style="max-width: 150px;" title="{{ $res->note }}">
                                        {{ $res->note }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-end px-4">
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Dəyiş
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <form action="{{ route('admin.reservations.status', $res->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button class="dropdown-item text-primary"><i class="fas fa-check-circle me-2"></i> Təsdiqlə</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.reservations.status', $res->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="completed">
                                                <button class="dropdown-item text-success"><i class="fas fa-check-double me-2"></i> Bitdi (Tamamlandı)</button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.reservations.status', $res->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button class="dropdown-item text-danger"><i class="fas fa-times-circle me-2"></i> Ləğv Et</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>

                                <form action="{{ route('admin.reservations.destroy', $res->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu rezervasiyanı silmək istədiyinizə əminsiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger ms-1" title="Sil"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-calendar-times fa-3x mb-3 text-gray-300"></i>
                                <p>Hələ heç bir rezervasiya yoxdur.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $reservations->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
