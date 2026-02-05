@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Sifarişlər</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-0 border-bottom-0 bg-white">
            <!-- TABLAR: Məhsullar vs Xidmətlər -->
            <ul class="nav nav-tabs card-header-tabs" id="orderTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $type == 'product' ? 'active font-weight-bold text-primary' : 'text-muted' }}"
                       href="{{ route('admin.orders.index', ['type' => 'product']) }}">
                       <i class="fas fa-box me-1"></i> Məhsul Sifarişləri
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $type == 'service' ? 'active font-weight-bold text-primary' : 'text-muted' }}"
                       href="{{ route('admin.orders.index', ['type' => 'service']) }}">
                       <i class="fas fa-briefcase me-1"></i> Xidmət Sifarişləri
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sifariş №</th>
                            <th>Müştəri</th>
                            <th>Tarix</th>
                            <th>Ümumi Məbləğ</th>
                            <th>Ödəniş</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="fw-bold text-primary">#{{ $order->order_number }}</td>
                            <td>
                                <div class="fw-bold">{{ $order->customer_name }}</div>
                                <div class="small text-muted">{{ $order->customer_phone }}</div>
                            </td>
                            <td>
                                {{ $order->created_at->format('d.m.Y') }}
                                <div class="small text-muted">{{ $order->created_at->format('H:i') }}</div>
                            </td>
                            <td class="fw-bold">{{ $order->total }} ₼</td>
                            <td>
                                @if($order->payment_status == 'paid')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success">Ödənilib</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-dark border border-warning">Ödənilməyib</span>
                                @endif
                                <div class="small text-muted mt-1">{{ ucfirst($order->payment_method) }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $order->status_badge }}">{{ $order->status_label }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info text-white me-1" title="Bax">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Sifarişi silmək istədiyinizə əminsiniz?');">
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
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-shopping-basket fa-3x mb-3 text-gray-300"></i>
                                <p class="mb-0">Hələ heç bir sifariş yoxdur.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
