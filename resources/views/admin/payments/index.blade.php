@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Ödəniş Tarixçəsi (Məhsullar)</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">Daxil Olan Ödənişlər</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Sifariş №</th>
                            <th>Müştəri</th>
                            <th>Məbləğ</th>
                            <th>Metod / Transaksiya</th>
                            <th>Tarix</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $payment->order_id) }}" class="fw-bold text-decoration-none">
                                    #{{ $payment->order->order_number }}
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $payment->order->customer_name }}</div>
                                <div class="small text-muted">{{ $payment->order->customer_phone }}</div>
                            </td>
                            <td class="fw-bold text-success">
                                {{ $payment->amount }} {{ $payment->currency }}
                            </td>
                            <td>
                                <div class="text-uppercase small fw-bold">{{ $payment->payment_method }}</div>
                                <div class="small text-muted font-monospace">{{ $payment->transaction_id ?? '-' }}</div>
                            </td>
                            <td>
                                {{ $payment->paid_at->format('d.m.Y') }}
                                <div class="small text-muted">{{ $payment->paid_at->format('H:i') }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $payment->status_badge }}">{{ $payment->status_label }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <form action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu ödəniş qeydini silmək istədiyinizə əminsiniz?');">
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
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-money-bill-wave fa-3x mb-3 text-gray-300"></i>
                                <p class="mb-0">Hələ heç bir ödəniş yoxdur.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $payments->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
