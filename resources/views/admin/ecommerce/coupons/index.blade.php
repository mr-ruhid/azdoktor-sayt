@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Kuponlar</h3>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Yeni Kupon
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
                            <th>Kod</th>
                            <th>Dəyər</th>
                            <th>Min. Səbət</th>
                            <th>Müddət</th>
                            <th>İstifadə</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace px-3 py-2 fs-6">{{ $coupon->code }}</span>
                            </td>
                            <td>
                                @if($coupon->type == 'percent')
                                    <span class="text-primary fw-bold">{{ $coupon->value }}%</span>
                                @else
                                    <span class="text-success fw-bold">{{ $coupon->value }} ₼</span>
                                @endif
                            </td>
                            <td>{{ $coupon->min_spend ? $coupon->min_spend.' ₼' : '-' }}</td>
                            <td>
                                @if($coupon->start_date || $coupon->end_date)
                                    <small>
                                        {{ $coupon->start_date ? $coupon->start_date->format('d.m.Y') : '...' }} -
                                        {{ $coupon->end_date ? $coupon->end_date->format('d.m.Y') : '...' }}
                                    </small>
                                @else
                                    <span class="text-muted small">Limitsiz</span>
                                @endif
                            </td>
                            <td>
                                {{ $coupon->used_count }}
                                <span class="text-muted small">/ {{ $coupon->usage_limit ?? '∞' }}</span>
                            </td>
                            <td class="text-center">
                                @if($coupon->isValid())
                                    <span class="badge bg-success">Aktiv</span>
                                @else
                                    <span class="badge bg-secondary">Deaktiv/Bitib</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu kuponu silmək istədiyinizə əminsiniz?');">
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
                            <td colspan="8" class="text-center py-5 text-muted">Hələ heç bir kupon yoxdur.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $coupons->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
