@extends('layouts.public')

@section('title', __('doctor.dashboard_title', ['default' => 'Həkim Paneli']))

@section('content')
<div class="container py-5">

    {{-- Başlıq --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h2 class="fw-bold mb-0 text-primary"><i class="fas fa-user-md me-2"></i> {{ __('doctor.dashboard_title', ['default' => 'Həkim Paneli']) }}</h2>
            <p class="text-muted mb-0">{{ __('doctor.welcome', ['default' => 'Xoş gəldiniz']) }}, Dr. {{ Auth::user()->name }} {{ Auth::user()->surname }}</p>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline-danger rounded-pill px-4"><i class="fas fa-sign-out-alt me-2"></i> {{ __('doctor.logout', ['default' => 'Çıxış']) }}</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Rezervasiya Cədvəli --}}
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header bg-light py-3 border-0">
            <h5 class="mb-0 fw-bold"><i class="far fa-calendar-check me-2 text-primary"></i> {{ __('doctor.incoming_reservations', ['default' => 'Gələn Rezervasiyalar']) }}</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-white border-bottom">
                    <tr>
                        <th class="ps-4 py-3 text-muted small text-uppercase">{{ __('doctor.date_time', ['default' => 'Tarix & Saat']) }}</th>
                        <th class="py-3 text-muted small text-uppercase">{{ __('doctor.patient', ['default' => 'Pasiyent']) }}</th>
                        <th class="py-3 text-muted small text-uppercase">{{ __('doctor.contact', ['default' => 'Əlaqə']) }}</th>
                        <th class="py-3 text-muted small text-uppercase">{{ __('doctor.note', ['default' => 'Qeyd']) }}</th>
                        <th class="py-3 text-center text-muted small text-uppercase">{{ __('doctor.status', ['default' => 'Status']) }}</th>
                        <th class="pe-4 py-3 text-end text-muted small text-uppercase">{{ __('doctor.actions', ['default' => 'Əməliyyatlar']) }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $res)
                        <tr class="{{ $res->status == 'pending' ? 'bg-warning bg-opacity-10' : '' }}">
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $res->reservation_date->format('d.m.Y') }}</div>
                                <div class="text-primary small fw-bold">
                                    <i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($res->reservation_time)->format('H:i') }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $res->name }}</div>
                                @if($res->user_id)
                                    <span class="badge bg-light text-dark border rounded-pill" style="font-size: 10px;">{{ __('doctor.registered', ['default' => 'Qeydiyyatlı']) }}</span>
                                @else
                                    <span class="badge bg-light text-secondary border rounded-pill" style="font-size: 10px;">{{ __('doctor.guest', ['default' => 'Qonaq']) }}</span>
                                @endif
                            </td>
                            <td>
                                <div><a href="tel:{{ $res->phone }}" class="text-decoration-none text-dark fw-medium">{{ $res->phone }}</a></div>
                                <small class="text-muted">{{ $res->email }}</small>
                            </td>
                            <td>
                                @if($res->note)
                                    <span class="d-inline-block text-truncate" style="max-width: 150px;" title="{{ $res->note }}">
                                        {{ $res->note }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($res->status == 'pending')
                                    <span class="badge bg-warning text-dark">{{ __('doctor.status_pending', ['default' => 'Gözləyir']) }}</span>
                                @elseif($res->status == 'confirmed')
                                    <span class="badge bg-success">{{ __('doctor.status_confirmed', ['default' => 'Təsdiqləndi']) }}</span>
                                @elseif($res->status == 'cancelled')
                                    <span class="badge bg-danger">{{ __('doctor.status_cancelled', ['default' => 'Ləğv edildi']) }}</span>
                                @elseif($res->status == 'completed')
                                    <span class="badge bg-secondary">{{ __('doctor.status_completed', ['default' => 'Bitdi']) }}</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group shadow-sm rounded-pill" role="group">
                                    @if($res->status != 'confirmed')
                                        <form action="{{ route('doctor.reservation.status', $res->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="confirmed">
                                            <button class="btn btn-sm btn-success rounded-start-pill px-3" title="{{ __('doctor.approve', ['default' => 'Təsdiqlə']) }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($res->status != 'cancelled')
                                        <form action="{{ route('doctor.reservation.status', $res->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button class="btn btn-sm btn-danger rounded-end-pill px-3" title="{{ __('doctor.cancel', ['default' => 'Ləğv et']) }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($res->status == 'confirmed')
                                         <form action="{{ route('doctor.reservation.status', $res->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="completed">
                                            <button class="btn btn-sm btn-secondary px-3" title="{{ __('doctor.mark_as_completed', ['default' => 'Bitdi kimi işarələ']) }}">
                                                <i class="fas fa-flag-checkered"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <i class="far fa-calendar-times fa-3x text-muted opacity-25 mb-3"></i>
                                    <h5 class="text-muted fw-normal">{{ __('doctor.no_reservations', ['default' => 'Hələ heç bir rezervasiya yoxdur.']) }}</h5>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginasiya --}}
        @if($reservations->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
                {{ $reservations->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
