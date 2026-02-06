@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Keş Təmizləmə Alətləri</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Application Cache -->
        <div class="col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tətbiq Keşi</div>
                            <div class="mb-0 font-weight-bold text-gray-800">Sistemin ümumi keş fayllarını təmizləyir.</div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.tools.cache.clear', 'application') }}" class="btn btn-primary btn-sm">Təmizlə</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Route Cache -->
        <div class="col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Route (Link) Keşi</div>
                            <div class="mb-0 font-weight-bold text-gray-800">Linklərdə (web.php) edilən dəyişikliklər görünmürsə istifadə edin.</div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.tools.cache.clear', 'route') }}" class="btn btn-success btn-sm">Təmizlə</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Config Cache -->
        <div class="col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Konfiqurasiya Keşi</div>
                            <div class="mb-0 font-weight-bold text-gray-800">.env faylındakı və ya sayt ayarlarındakı dəyişikliklər üçün.</div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.tools.cache.clear', 'config') }}" class="btn btn-info btn-sm text-white">Təmizlə</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Cache -->
        <div class="col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Görüntü (View) Keşi</div>
                            <div class="mb-0 font-weight-bold text-gray-800">Blade fayllarında (HTML) edilən dəyişikliklər görünmürsə.</div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.tools.cache.clear', 'view') }}" class="btn btn-warning btn-sm text-dark">Təmizlə</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Optimize All -->
        <div class="col-12 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Tam Təmizlik (Optimize:Clear)</div>
                            <div class="mb-0 font-weight-bold text-gray-800">Yuxarıdakı bütün keşləri birdəfəlik silir.</div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.tools.cache.clear', 'optimize') }}" class="btn btn-danger">Hamsını Təmizlə</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
