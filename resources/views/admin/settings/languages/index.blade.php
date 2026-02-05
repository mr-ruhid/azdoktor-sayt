@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Yeni Dil Əlavə Etmə Formu -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Yeni Dil Əlavə Et</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('languages.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Dilin Adı</label>
                            <input type="text" name="name" class="form-control" placeholder="Məs: Türkçə" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dil Kodu (ISO)</label>
                            <input type="text" name="code" class="form-control" placeholder="Məs: tr" maxlength="5" required>
                            <small class="text-muted">URL-də görünəcək (site.com/tr)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Yazı İstiqaməti</label>
                            <select name="direction" class="form-select">
                                <option value="ltr">Soldan Sağa (LTR)</option>
                                <option value="rtl">Sağdan Sola (RTL - Ərəb)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Yadda Saxla</button>
                    </form>
                </div>
            </div>

            <!-- Xəta və Mesajlar -->
            @if(session('success'))
                <div class="alert alert-success mt-3">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Mövcud Dillərin Siyahısı -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-globe"></i> Aktiv Dillər</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Adı</th>
                                <th>Kodu</th>
                                <th>İstiqamət</th>
                                <th>Status</th>
                                <th class="text-end">Əməliyyatlar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($languages as $lang)
                            <tr class="{{ $lang->is_default ? 'table-primary' : '' }}">
                                <td class="align-middle">
                                    <strong>{{ $lang->name }}</strong>
                                    @if($lang->is_default)
                                        <span class="badge bg-primary ms-1">Varsayılan</span>
                                    @endif
                                </td>
                                <td class="align-middle text-uppercase">{{ $lang->code }}</td>
                                <td class="align-middle">{{ strtoupper($lang->direction) }}</td>
                                <td class="align-middle">
                                    @if($lang->is_default)
                                        <span class="badge bg-success">Aktiv</span>
                                    @else
                                        <form action="{{ route('languages.update', $lang->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="{{ $lang->status ? 0 : 1 }}">
                                            <button type="submit" class="btn btn-sm {{ $lang->status ? 'btn-outline-success' : 'btn-outline-secondary' }}">
                                                {{ $lang->status ? 'Aktiv' : 'Deaktiv' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="align-middle text-end">
                                    <div class="btn-group" role="group">
                                        <!-- Yeni: Tərcümə Düyməsi -->
                                        <a href="{{ route('languages.translate', $lang->id) }}" class="btn btn-sm btn-info text-white" title="Sözləri Tərcümə Et">
                                            <i class="fas fa-language"></i>
                                        </a>

                                        <!-- Varsayılan Et Düyməsi -->
                                        @if(!$lang->is_default && $lang->status)
                                            <form action="{{ route('languages.update', $lang->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="set_default" value="1">
                                                <button type="submit" class="btn btn-sm btn-light" title="Varsayılan Et">
                                                    <i class="fas fa-star text-warning"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Silmək Düyməsi -->
                                        @if(!$lang->is_default)
                                            <form action="{{ route('languages.destroy', $lang->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu dili silmək istədiyinizə əminsiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light text-danger" title="Sil">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
