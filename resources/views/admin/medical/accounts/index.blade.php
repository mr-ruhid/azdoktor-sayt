@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Həkim Hesabları</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">Sistemdəki Həkimlər</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Həkim</th>
                            <th>Klinika</th>
                            <th>Hesab Statusu</th>
                            <th>Login Email</th>
                            <th class="text-end">Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctors as $doctor)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($doctor->image)
                                        <img src="{{ asset($doctor->image) }}" class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user-md text-secondary"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $doctor->full_name }}</div>
                                        <small class="text-muted">{{ $doctor->specialty->name ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $doctor->clinic->name ?? '-' }}</td>
                            <td>
                                @if($doctor->user_id)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i> Aktiv Hesab</span>
                                @else
                                    <span class="badge bg-secondary">Hesab Yoxdur</span>
                                @endif
                            </td>
                            <td>
                                {{ $doctor->user->email ?? '-' }}
                            </td>
                            <td class="text-end">
                                <!-- Hesab Yarat / Düzəlt Düyməsi -->
                                <button class="btn btn-sm {{ $doctor->user_id ? 'btn-warning' : 'btn-primary' }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#accountModal{{ $doctor->id }}">
                                    <i class="fas {{ $doctor->user_id ? 'fa-key' : 'fa-plus' }}"></i>
                                    {{ $doctor->user_id ? 'Şifrəni Dəyiş' : 'Hesab Yarat' }}
                                </button>

                                <!-- Hesabı Sil Düyməsi -->
                                @if($doctor->user_id)
                                    <form action="{{ route('admin.doctor_accounts.destroy', $doctor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Həkimin giriş hesabını silmək istədiyinizə əminsiniz? Həkimin öz profili silinməyəcək.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger ms-1" title="Girişi Ləğv Et">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="accountModal{{ $doctor->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.doctor_accounts.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                @if($doctor->user_id)
                                                    <i class="fas fa-edit text-warning me-2"></i> Hesabı Yenilə
                                                @else
                                                    <i class="fas fa-user-plus text-primary me-2"></i> Yeni Hesab Yarat
                                                @endif
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-info py-2 small">
                                                Bu hesab <strong>"Doctor"</strong> rolu ilə yaradılacaq/yenilənəcək.
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Login Email</label>
                                                <input type="email" name="email" class="form-control"
                                                       value="{{ $doctor->user->email ?? $doctor->email }}" required>
                                                <small class="text-muted">Həkim sistemə bu email ilə girəcək.</small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Şifrə
                                                    @if($doctor->user_id) <span class="text-muted small">(Boş qalsa dəyişməyəcək)</span> @endif
                                                </label>
                                                <input type="text" name="password" class="form-control"
                                                       {{ !$doctor->user_id ? 'required' : '' }} minlength="6">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bağla</button>
                                            <button type="submit" class="btn {{ $doctor->user_id ? 'btn-warning' : 'btn-primary' }}">
                                                Yadda Saxla
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Hələ heç bir həkim yoxdur. Öncə həkim əlavə edin.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $doctors->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
