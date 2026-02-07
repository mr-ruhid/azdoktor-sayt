@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Səhifələr</h1>
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni Səhifə
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Başlıq</th>
                            <th>Slug (URL)</th>
                            <th>Tip</th>
                            <th>Status</th>
                            <th>Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pages as $page)
                            <tr>
                                <td>{{ $page->getTranslation('title', app()->getLocale()) }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $page->slug }}</span></td>
                                <td>
                                    @if($page->is_standard)
                                        <span class="badge bg-warning text-dark"><i class="fas fa-lock"></i> Standart</span>
                                    @else
                                        <span class="badge bg-info">Xüsusi</span>
                                    @endif
                                </td>
                                <td>
                                    @if($page->status) <i class="fas fa-check-circle text-success"></i>
                                    @else <i class="fas fa-times-circle text-danger"></i> @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if(!$page->is_standard)
                                        <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="d-inline on-delete-confirm">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled title="Silinə bilməz"><i class="fas fa-trash"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
