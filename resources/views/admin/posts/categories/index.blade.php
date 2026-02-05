@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Paylaşım Kateqoriyaları</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Yeni Kateqoriya
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
                            <th>Adı ({{ app()->getLocale() }})</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th class="text-end">Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $category->name }}</td>
                            <td class="text-muted small">{{ $category->slug }}</td>
                            <td>
                                @if($category->status)
                                    <span class="badge bg-success">Aktiv</span>
                                @else
                                    <span class="badge bg-secondary">Deaktiv</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-info text-white me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $category->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmək istədiyinizə əminsiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Redaktə Et: {{ $category->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <ul class="nav nav-tabs mb-3" role="tablist">
                                                @foreach($languages as $index => $lang)
                                                    <li class="nav-item">
                                                        <button class="nav-link {{ $index == 0 ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#edit-lang-{{ $lang->code }}-{{ $category->id }}" type="button">
                                                            {{ $lang->code }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="tab-content">
                                                @foreach($languages as $index => $lang)
                                                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="edit-lang-{{ $lang->code }}-{{ $category->id }}">
                                                        <div class="mb-3">
                                                            <label class="form-label">Ad ({{ $lang->name }})</label>
                                                            <input type="text" name="name[{{ $lang->code }}]" class="form-control" value="{{ $category->getTranslation('name', $lang->code, false) }}">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="mb-3 form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="status" value="1" {{ $category->status ? 'checked' : '' }}>
                                                <label class="form-check-label">Status</label>
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
                            <td colspan="5" class="text-center text-muted py-4">Kateqoriya yoxdur.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $categories->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Yeni Kateqoriya</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
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
                                    <input type="text" name="name[{{ $lang->code }}]" class="form-control" {{ $lang->is_default ? 'required' : '' }}>
                                </div>
                            </div>
                        @endforeach
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
