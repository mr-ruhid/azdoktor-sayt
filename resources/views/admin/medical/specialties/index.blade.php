@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">İxtisaslar (Specialties)</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Yeni İxtisas
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px">#</th>
                            <th style="width: 80px">İkon</th>
                            <th>Adı ({{ app()->getLocale() }})</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th class="text-end">Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($specialties as $specialty)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-center">
                                @if($specialty->icon)
                                    <img src="{{ asset($specialty->icon) }}" width="40" height="40" class="rounded object-fit-cover">
                                @else
                                    <span class="text-muted"><i class="fas fa-image fa-lg"></i></span>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $specialty->name }}</td>
                            <td class="text-muted small">{{ $specialty->slug }}</td>
                            <td>
                                @if($specialty->status)
                                    <span class="badge bg-success">Aktiv</span>
                                @else
                                    <span class="badge bg-secondary">Deaktiv</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-info text-white me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $specialty->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.specialties.destroy', $specialty->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmək istədiyinizə əminsiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal (Hər sətir üçün ayrı) -->
                        <div class="modal fade" id="editModal{{ $specialty->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.specialties.update', $specialty->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Redaktə Et: {{ $specialty->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Dil Tabları -->
                                            <ul class="nav nav-tabs mb-3" role="tablist">
                                                @foreach($languages as $index => $lang)
                                                    <li class="nav-item">
                                                        <button class="nav-link {{ $index == 0 ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#edit-lang-{{ $lang->code }}-{{ $specialty->id }}" type="button">
                                                            {{ $lang->code }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="tab-content">
                                                @foreach($languages as $index => $lang)
                                                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="edit-lang-{{ $lang->code }}-{{ $specialty->id }}">
                                                        <div class="mb-3">
                                                            <label class="form-label">Ad ({{ $lang->name }})</label>
                                                            <input type="text" name="name[{{ $lang->code }}]" class="form-control" value="{{ $specialty->getTranslation('name', $lang->code, false) }}">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">İkon Dəyiş</label>
                                                <input type="file" name="icon" class="form-control">
                                                @if($specialty->icon)
                                                    <div class="mt-2">
                                                        <img src="{{ asset($specialty->icon) }}" width="50" class="rounded">
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="mb-3 form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="status" value="1" {{ $specialty->status ? 'checked' : '' }}>
                                                <label class="form-check-label">Status (Aktiv/Deaktiv)</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bağla</button>
                                            <button type="submit" class="btn btn-primary">Yadda Saxla</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Məlumat yoxdur.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $specialties->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.specialties.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Yeni İxtisas Yarat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <!-- Dil Tabları -->
                    <ul class="nav nav-tabs mb-3" id="createTabs" role="tablist">
                        @foreach($languages as $index => $lang)
                            <li class="nav-item">
                                <button class="nav-link {{ $index == 0 ? 'active' : '' }}" id="tab-{{ $lang->code }}" data-bs-toggle="tab" data-bs-target="#lang-{{ $lang->code }}" type="button">
                                    {{ $lang->code }}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        @foreach($languages as $index => $lang)
                            <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="lang-{{ $lang->code }}">
                                <div class="mb-3">
                                    <label class="form-label">Ad ({{ $lang->name }})</label>
                                    <input type="text" name="name[{{ $lang->code }}]" class="form-control" placeholder="Məs: Kardiologiya" {{ $lang->is_default ? 'required' : '' }}>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mb-3">
                        <label class="form-label">İkon</label>
                        <input type="file" name="icon" class="form-control">
                    </div>

                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" value="1" checked>
                        <label class="form-check-label">Status (Aktiv)</label>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bağla</button>
                    <button type="submit" class="btn btn-success">Yarat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
