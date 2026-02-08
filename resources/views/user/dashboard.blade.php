@extends('layouts.public')

@section('title', __('user.my_account', ['default' => 'Hesabım']))

@section('content')
<div class="container py-5">
    <div class="row">
        {{-- SOL TƏRƏF: Menyu --}}
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body text-center p-4 bg-primary text-white">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 fw-bold fs-3 shadow-sm" style="width: 80px; height: 80px;">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ Auth::user()->full_name }}</h5>
                    <small class="opacity-75">{{ Auth::user()->email }}</small>
                </div>
                <div class="list-group list-group-flush py-2">
                    <a href="#profile" data-bs-toggle="list" class="list-group-item list-group-item-action border-0 px-4 py-3 active fw-medium">
                        <i class="fas fa-user-cog me-2 text-primary"></i> {{ __('user.profile_settings', ['default' => 'Profil Ayarları']) }}
                    </a>
                    <a href="#reservations" data-bs-toggle="list" class="list-group-item list-group-item-action border-0 px-4 py-3 fw-medium">
                        <i class="fas fa-calendar-check me-2 text-warning"></i> {{ __('user.my_reservations', ['default' => 'Rezervasiyalarım']) }}
                    </a>
                    <a href="#orders" data-bs-toggle="list" class="list-group-item list-group-item-action border-0 px-4 py-3 fw-medium">
                        <i class="fas fa-shopping-bag me-2 text-success"></i> {{ __('user.my_orders', ['default' => 'Sifarişlərim']) }}
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="d-block">
                        @csrf
                        <button class="list-group-item list-group-item-action border-0 px-4 py-3 fw-medium text-danger w-100 text-start">
                            <i class="fas fa-sign-out-alt me-2"></i> {{ __('user.logout', ['default' => 'Çıxış']) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- SAĞ TƏRƏF: Məzmun --}}
        <div class="col-lg-9">
            <div class="tab-content">

                {{-- TAB 1: Profil Ayarları --}}
                <div class="tab-pane fade show active" id="profile">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 border-bottom-0">
                            <h5 class="mb-0 fw-bold">{{ __('user.profile_details', ['default' => 'Profil Məlumatları']) }}</h5>
                        </div>
                        <div class="card-body p-4">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('user.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('user.name', ['default' => 'Ad']) }}</label>
                                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('user.surname', ['default' => 'Soyad']) }}</label>
                                        <input type="text" name="surname" class="form-control" value="{{ $user->surname }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('user.email', ['default' => 'E-poçt']) }}</label>
                                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('user.phone', ['default' => 'Telefon']) }}</label>
                                        <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('user.birth_date', ['default' => 'Doğum Tarixi']) }}</label>
                                        <input type="date" name="birth_date" class="form-control" value="{{ $user->birth_date ? $user->birth_date->format('Y-m-d') : '' }}">
                                    </div>
                                    <div class="col-12">
                                        <hr class="my-3">
                                        <h6 class="fw-bold mb-3">{{ __('user.change_password', ['default' => 'Şifrəni Dəyiş']) }} <small class="text-muted fw-normal">({{ __('user.password_hint', ['default' => 'Yalnız dəyişmək istəsəniz yazın']) }})</small></h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('user.new_password', ['default' => 'Yeni Şifrə']) }}</label>
                                        <input type="password" name="password" class="form-control" autocomplete="new-password">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('user.confirm_password', ['default' => 'Şifrə Təsdiqi']) }}</label>
                                        <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                                    </div>
                                    <div class="col-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">{{ __('user.save_changes', ['default' => 'Yadda Saxla']) }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- TAB 2: Rezervasiyalar --}}
                <div class="tab-pane fade" id="reservations">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 border-bottom-0">
                            <h5 class="mb-0 fw-bold">{{ __('user.my_reservations', ['default' => 'Rezervasiyalarım']) }}</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4">{{ __('user.doctor', ['default' => 'Həkim']) }}</th>
                                            <th>{{ __('user.date', ['default' => 'Tarix']) }}</th>
                                            <th>{{ __('user.time', ['default' => 'Saat']) }}</th>
                                            <th>{{ __('user.status', ['default' => 'Status']) }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($reservations as $res)
                                            <tr>
                                                <td class="ps-4">
                                                    @if($res->doctor)
                                                        <div class="fw-bold">Dr. {{ $res->doctor->full_name }}</div>
                                                        <small class="text-muted">{{ $res->doctor->specialty->name ?? '-' }}</small>
                                                    @else
                                                        <span class="text-muted">{{ __('user.doctor_deleted', ['default' => 'Həkim silinib']) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $res->reservation_date->format('d.m.Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($res->reservation_time)->format('H:i') }}</td>
                                                <td>
                                                    @if($res->status == 'pending') <span class="badge bg-warning text-dark">{{ __('user.status_pending', ['default' => 'Gözləyir']) }}</span>
                                                    @elseif($res->status == 'confirmed') <span class="badge bg-success">{{ __('user.status_confirmed', ['default' => 'Təsdiqləndi']) }}</span>
                                                    @elseif($res->status == 'cancelled') <span class="badge bg-danger">{{ __('user.status_cancelled', ['default' => 'Ləğv edildi']) }}</span>
                                                    @elseif($res->status == 'completed') <span class="badge bg-secondary">{{ __('user.status_completed', ['default' => 'Bitdi']) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5 text-muted">{{ __('user.no_reservations', ['default' => 'Rezervasiya tapılmadı.']) }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($reservations->hasPages())
                                <div class="p-3">
                                    {{ $reservations->appends(['orders_page' => $orders->currentPage()])->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- TAB 3: Sifarişlər --}}
                <div class="tab-pane fade" id="orders">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 border-bottom-0">
                            <h5 class="mb-0 fw-bold">{{ __('user.my_orders', ['default' => 'Sifarişlərim']) }}</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4">{{ __('user.order_id', ['default' => 'Sifariş ID']) }}</th>
                                            <th>{{ __('user.date', ['default' => 'Tarix']) }}</th>
                                            <th>{{ __('user.amount', ['default' => 'Məbləğ']) }}</th>
                                            <th>{{ __('user.status', ['default' => 'Status']) }}</th>
                                            <th>{{ __('user.details', ['default' => 'Detallar']) }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $order)
                                            <tr>
                                                <td class="ps-4 fw-bold">#{{ $order->id }}</td>
                                                <td>{{ $order->created_at->format('d.m.Y') }}</td>
                                                <td>{{ $order->total_amount }} ₼</td>
                                                <td>
                                                    @if($order->status == 'pending') <span class="badge bg-warning text-dark">{{ __('user.status_pending', ['default' => 'Gözləyir']) }}</span>
                                                    @elseif($order->status == 'completed') <span class="badge bg-success">{{ __('user.status_completed', ['default' => 'Tamamlandı']) }}</span>
                                                    @elseif($order->status == 'cancelled') <span class="badge bg-danger">{{ __('user.status_cancelled', ['default' => 'Ləğv edildi']) }}</span>
                                                    @else <span class="badge bg-secondary">{{ $order->status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary rounded-pill">{{ __('user.view', ['default' => 'Bax']) }}</button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5 text-muted">{{ __('user.no_orders', ['default' => 'Sifariş tapılmadı.']) }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($orders->hasPages())
                                <div class="p-3">
                                    {{ $orders->appends(['reservations_page' => $reservations->currentPage()])->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
