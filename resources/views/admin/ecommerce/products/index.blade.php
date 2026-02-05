@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Məhsullar</h3>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Yeni Məhsul
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
                            <th>Məhsul Adı</th>
                            <th>Qiymət</th>
                            <th>Stok</th>
                            <th>Kateqoriya</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" class="rounded border" width="50" height="50" style="object-fit: cover;">
                                @else
                                    <div class="bg-light rounded border d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-box text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $product->name }}</div>
                                <div class="small text-muted">SKU: {{ $product->sku ?? '-' }}</div>
                            </td>
                            <td>
                                @if($product->sale_price)
                                    <span class="text-danger fw-bold">{{ $product->sale_price }} ₼</span>
                                    <small class="text-muted text-decoration-line-through">{{ $product->price }} ₼</small>
                                @else
                                    <span class="fw-bold">{{ $product->price }} ₼</span>
                                @endif
                            </td>
                            <td>
                                @if($product->stock_quantity > 0)
                                    <span class="badge bg-success bg-opacity-10 text-success">{{ $product->stock_quantity }} ədəd</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger">Bitib</span>
                                @endif
                            </td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td class="text-center">
                                @if($product->status)
                                    <span class="badge bg-success">Aktiv</span>
                                @else
                                    <span class="badge bg-secondary">Deaktiv</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu məhsulu silmək istədiyinizə əminsiniz?');">
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
                            <td colspan="8" class="text-center py-5 text-muted">Məhsul tapılmadı.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
