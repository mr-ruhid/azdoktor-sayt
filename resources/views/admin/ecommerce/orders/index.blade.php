@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Sifarişlər</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">Gələn Sifarişlər</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sifariş №</th>
                            <th>Müştəri</th>
                            <th>Tarix</th>
                            <th>Məbləğ</th>
                            <th>Ödəniş</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="fw-bold text-primary">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-decoration-none">
                                    #{{ $order->order_number }}
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $order->customer_name }}</div>
                                <div class="small text-muted"><i class="fas fa-phone-alt me-1"></i> {{ $order->customer_phone }}</div>
                            </td>
                            <td>
                                {{ $order->created_at->format('d.m.Y') }}
                                <div class="small text-muted">{{ $order->created_at->format('H:i') }}</div>
                            </td>
                            <td class="fw-bold">{{ $order->total }} ₼</td>
                            <td>
                                <div class="d-flex flex-column">
                                    @if($order->payment_status == 'paid')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success mb-1" style="width: fit-content;">Ödənilib</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-dark border border-warning mb-1" style="width: fit-content;">Gözləyir</span>
                                    @endif
                                    <small class="text-muted">{{ $order->payment_method == 'card' ? 'Kartla' : 'Nağd' }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $order->status_badge }}">{{ $order->status_label }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info text-white" title="Detallar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu sifarişi silmək istədiyinizə əminsiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger border-start-0" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-shopping-basket fa-3x mb-3 text-gray-300"></i>
                                <p class="mb-0">Hələ heç bir sifariş yoxdur.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginasiya --}}
            @if($orders->hasPages())
                <div class="d-flex justify-content-center mt-4 mb-3">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
