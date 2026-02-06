@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Giriş Logları və Təhlükəsizlik</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Log Siyahısı -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Son Girişlər</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>İstifadəçi</th>
                                    <th>IP Ünvan</th>
                                    <th>Tarix</th>
                                    <th class="text-end pe-4">Əməliyyat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    @php
                                        // Bu IP son 15 gündə istifadə edilibmi? (Etibarlıdır?)
                                        // Qeyd: Bu sorğu performansı azalda bilər, real layihədə controller-dən gəlməsi tövsiyə olunur.
                                        $isTrusted = \App\Models\LoginLog::where('ip_address', $log->ip_address)
                                                        ->where('login_at', '>=', now()->subDays(15))
                                                        ->count() > 0;

                                        // Artıq bloklanıbmı?
                                        $isBlocked = $blockedIps->contains('ip_address', $log->ip_address);
                                    @endphp
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $log->user->name ?? 'Naməlum' }}</div>
                                        <small class="text-muted" title="{{ $log->user_agent }}">
                                            {{ Str::limit($log->user_agent, 30) }}
                                        </small>
                                    </td>
                                    <td class="font-monospace">
                                        {{ $log->ip_address }}
                                        @if($log->ip_address == request()->ip())
                                            <span class="badge bg-success ms-1">Siz</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->login_at->format('d.m.Y H:i') }}</td>
                                    <td class="text-end pe-4">
                                        @if($isBlocked)
                                            <span class="badge bg-danger">Bloklanıb</span>
                                        @elseif($log->ip_address == request()->ip())
                                            <button class="btn btn-sm btn-secondary disabled" style="opacity: 0.6; cursor: not-allowed;">Cari IP</button>
                                        @elseif($isTrusted)
                                            <button class="btn btn-sm btn-light border text-success" disabled title="Bu IP son 15 gündə istifadə edildiyi üçün etibarlıdır və bloklana bilməz.">
                                                <i class="fas fa-shield-alt me-1"></i> Etibarlı
                                            </button>
                                        @else
                                            <form action="{{ route('admin.logs.block') }}" method="POST" class="d-inline" onsubmit="return confirm('Bu IP-ni bloklamaq istədiyinizə əminsiniz?');">
                                                @csrf
                                                <input type="hidden" name="ip_address" value="{{ $log->ip_address }}">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-ban me-1"></i> Blokla
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Hələ heç bir giriş qeydə alınmayıb.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $logs->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Bloklanmış IP-lər -->
        <div class="col-lg-4">
            <div class="card shadow mb-4 border-left-danger">
                <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-ban me-2"></i> Bloklanmış IP-lər</h6>
                    <span class="badge bg-danger rounded-pill">{{ $blockedIps->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @if($blockedIps->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($blockedIps as $bIp)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-3">
                                    <div>
                                        <div class="fw-bold font-monospace text-dark">{{ $bIp->ip_address }}</div>
                                        <small class="text-muted" style="font-size: 11px;">
                                            {{ $bIp->reason }} <br>
                                            {{ $bIp->created_at->format('d.m.Y') }}
                                        </small>
                                    </div>
                                    <form action="{{ route('admin.logs.unblock', $bIp->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-light text-success border" title="Bloku Aç">
                                            <i class="fas fa-unlock"></i>
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-check-circle fa-3x mb-3 text-success opacity-50"></i>
                            <p>Bloklanmış IP yoxdur.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
