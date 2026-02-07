@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Yan Panellər və Navbar</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @foreach($sidebars as $sidebar)
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100 border-left-{{ $sidebar->type == 'pc_sidebar' ? 'primary' : 'success' }}">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($sidebar->type == 'pc_sidebar')
                            <i class="fas fa-columns fa-4x text-primary"></i>
                        @else
                            <i class="fas fa-mobile-alt fa-4x text-success"></i>
                        @endif
                    </div>

                    <h5 class="font-weight-bold">{{ $sidebar->name }}</h5>
                    <p class="text-muted small">
                        @if($sidebar->type == 'pc_sidebar')
                            Masaüstü versiyada sol tərəfdə görünən əsas menyu və logo sahəsi.
                        @else
                            Mobil versiyada yuxarıda görünən axtarış və menyu sahəsi.
                        @endif
                    </p>

                    <div class="mb-3">
                        <span class="badge {{ $sidebar->status ? 'bg-success' : 'bg-secondary' }}">
                            {{ $sidebar->status ? 'Aktiv' : 'Deaktiv' }}
                        </span>
                    </div>

                    <a href="{{ route('admin.sidebars.edit', $sidebar->id) }}" class="btn btn-primary">
                        <i class="fas fa-cog me-1"></i> Tənzimlə
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
