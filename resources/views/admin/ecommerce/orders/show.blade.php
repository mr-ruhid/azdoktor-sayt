@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Sifariş #{{ $order->order_number }}</h3>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Geri
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <!-- Sifariş Məhsulları -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Sifarişin Tərkibi</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Məhsul / Xidmət</th>
                                    <th class="text-center">Qiymət</th>
                                    <th class="text-center">Say</th>
                                    <th class="text-end pe-4">Cəm</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $item->name }}</div>
                                        <small class="text-muted">
                                            @if($item->orderable_type)
                                                {{ class_basename($item->orderable_type) == 'Product' ? 'Məhsul' : 'Xidmət' }}
                                            @else
                                                Məhsul
                                            @endif
                                        </small>
                                    </td>
                                    <td class="text-center">{{ $item->price }} ₼</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end pe-4 fw-bold">{{ $item->total }} ₼</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Ara Cəm:</td>
                                    <td class="text-end pe-4">{{ $order->subtotal }} ₼</td>
                                </tr>
                                @if($order->discount > 0)
                                <tr>
                                    <td colspan="3" class="text-end fw-bold text-success">Endirim:</td>
                                    <td class="text-end pe-4 text-success">-{{ $order->discount }} ₼</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-end fw-bold h5">Yekun:</td>
                                    <td class="text-end pe-4 fw-bold h5 text-primary">{{ $order->total }} ₼</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status və Müştəri -->
        <div class="col-lg-4">
            <!-- Status İdarəetməsi -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Status İdarəetməsi</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Sifariş Statusu</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Gözləyir</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Hazırlanır</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Ləğv edildi</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ödəniş Statusu</label>
                            <select name="payment_status" class="form-select">
                                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Ödənilməyib</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Ödənilib</option>
                                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Uğursuz / Ləğv</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Yenilə</button>
                    </form>
                </div>
            </div>

            <!-- Müştəri Məlumatları -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Müştəri Məlumatları</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-user me-2 text-muted"></i> <strong>{{ $order->customer_name }}</strong>
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-phone me-2 text-muted"></i> <a href="tel:{{ $order->customer_phone }}">{{ $order->customer_phone }}</a>
                    </div>
                    @if($order->customer_email)
                    <div class="mb-2">
                        <i class="fas fa-envelope me-2 text-muted"></i> <a href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a>
                    </div>
                    @endif
                    <hr>
                    <div class="mb-2">
                        <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                        {{ $order->customer_address ?? 'Ünvan qeyd edilməyib' }}
                    </div>
                    @if($order->note)
                    <div class="alert alert-warning mt-3 mb-0 small">
                        <i class="fas fa-sticky-note me-1"></i> <strong>Qeyd:</strong> {{ $order->note }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
