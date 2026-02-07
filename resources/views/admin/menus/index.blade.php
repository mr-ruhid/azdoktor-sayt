@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Menyu Builder</h1>
        <a href="{{ route('admin.menus.create', ['type' => $currentType]) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Yeni Menyu
        </a>
    </div>

    {{-- Tablar (PC və Mobil keçidi) --}}
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ $currentType == 'pc_sidebar' ? 'active' : '' }}"
               href="{{ route('admin.menus.index', ['type' => 'pc_sidebar']) }}">
               <i class="fas fa-desktop me-2"></i> PC Sidebar
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $currentType == 'mobile_navbar' ? 'active' : '' }}"
               href="{{ route('admin.menus.index', ['type' => 'mobile_navbar']) }}">
               <i class="fas fa-mobile-alt me-2"></i> Mobile Navbar
            </a>
        </li>
    </ul>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if($menus->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="menuTable">
                        <thead class="table-light">
                            <tr>
                                <th width="50"><i class="fas fa-arrows-alt"></i></th>
                                <th>Başlıq ({{ app()->getLocale() }})</th>
                                <th>URL</th>
                                <th>Görünürlük (Rol)</th>
                                <th>Status</th>
                                <th width="150">Əməliyyatlar</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-menus">
                            @foreach($menus as $menu)
                                {{-- Əsas Menyu --}}
                                <tr data-id="{{ $menu->id }}" class="bg-light">
                                    <td class="text-center grab-cursor"><i class="fas fa-bars text-muted"></i></td>
                                    <td class="fw-bold">
                                        <i class="{{ $menu->icon }} me-2 text-primary"></i>
                                        {{ $menu->getTranslation('title', app()->getLocale()) }}
                                    </td>
                                    <td><code>{{ $menu->url }}</code></td>
                                    <td>
                                        @if($menu->role == 'all') <span class="badge bg-secondary">Hamı</span>
                                        @elseif($menu->role == 'guest') <span class="badge bg-warning text-dark">Qonaq</span>
                                        @elseif($menu->role == 'auth_user') <span class="badge bg-info">İstifadəçi</span>
                                        @elseif($menu->role == 'doctor') <span class="badge bg-success">Həkim</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($menu->status) <span class="badge bg-success">Aktiv</span>
                                        @else <span class="badge bg-danger">Deaktiv</span> @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" class="d-inline on-delete-confirm">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- Alt Menyular (Sub-menus) --}}
                                @foreach($menu->children as $child)
                                    <tr data-id="{{ $child->id }}">
                                        <td class="text-center grab-cursor"><i class="fas fa-bars text-muted small"></i></td>
                                        <td class="ps-5">
                                            <i class="fas fa-level-up-alt fa-rotate-90 me-2 text-muted"></i>
                                            <i class="{{ $child->icon }} me-1"></i>
                                            {{ $child->getTranslation('title', app()->getLocale()) }}
                                        </td>
                                        <td><code>{{ $child->url }}</code></td>
                                        <td><small class="text-muted">Parentdən asılıdır</small></td>
                                        <td>
                                            @if($child->status) <i class="fas fa-check text-success"></i>
                                            @else <i class="fas fa-times text-danger"></i> @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.menus.edit', $child->id) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.menus.destroy', $child->id) }}" method="POST" class="d-inline on-delete-confirm">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted">Bu bölmədə heç bir menyu tapılmadı.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@section('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
    $(function() {
        $("#sortable-menus").sortable({
            placeholder: "ui-state-highlight",
            handle: ".grab-cursor",
            update: function(event, ui) {
                var order = [];
                $('#sortable-menus tr').each(function(index, element) {
                    order.push({
                        id: $(this).data('id'),
                        position: index + 1
                    });
                });

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{{ route('admin.menus.sort') }}",
                    data: {
                        order: order,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        toastr.success('Sıralama yadda saxlanıldı');
                    }
                });
            }
        });
    });
</script>
<style>
    .grab-cursor { cursor: grab; }
    .grab-cursor:active { cursor: grabbing; }
    .ui-state-highlight { height: 50px; background: #f8f9fa; border: 1px dashed #ccc; }
</style>
@endsection

@endsection
