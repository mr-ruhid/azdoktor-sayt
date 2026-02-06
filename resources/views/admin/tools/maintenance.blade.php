@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Sistem Qulluğu</h3>
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
        <!-- Sistem Məlumatları -->
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Sistem Məlumatları</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>PHP Versiyası</span>
                            <span class="badge bg-secondary">{{ $systemInfo['php'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Laravel Versiyası</span>
                            <span class="badge bg-danger">{{ $systemInfo['laravel'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Server Tarixi</span>
                            <small class="text-muted font-monospace">{{ $systemInfo['time'] }}</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Log Faylının Ölçüsü</span>
                            <span class="badge bg-warning text-dark">{{ $systemInfo['log_size'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Baza Ölçüsü</span>
                            <span class="badge bg-info">{{ $systemInfo['database_size'] }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Əməliyyatlar -->
        <div class="col-md-8 mb-4">
            <div class="row">
                <!-- Storage Link -->
                <div class="col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Fayl Sistemini Düzəlt (Storage Link)</div>
                                    <div class="mb-0 font-weight-bold text-gray-800 small">Saytda şəkillər görünmürsə, bu düyməni sıxın.</div>
                                </div>
                                <div class="col-auto">
                                    <form action="{{ route('admin.tools.maintenance.action', 'storage_link') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-info btn-sm text-white">Düzəlt</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Log Təmizlə -->
                <div class="col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Logları Təmizlə</div>
                                    <div class="mb-0 font-weight-bold text-gray-800 small">`laravel.log` faylını sıfırlayır və yer açır.</div>
                                </div>
                                <div class="col-auto">
                                    <form action="{{ route('admin.tools.maintenance.action', 'clear_logs') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm text-dark">Təmizlə</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Miqrasiya -->
                <div class="col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Verilənlər Bazası (Migrate)</div>
                                    <div class="mb-0 font-weight-bold text-gray-800 small">Cədvəllərdə çatışmazlıq varsa, düzəldir.</div>
                                </div>
                                <div class="col-auto">
                                    <form action="{{ route('admin.tools.maintenance.action', 'migrate') }}" method="POST" onsubmit="return confirm('Bu əməliyyat bazaya yeni cədvəllər əlavə edəcək. Davam edilsin?');">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Yoxla</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sessiyaları Təmizlə -->
                <div class="col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Bütün Sessiyaları Sıfırla</div>
                                    <div class="mb-0 font-weight-bold text-gray-800 small">Bütün istifadəçiləri (Admin daxil) sistemdən çıxarır.</div>
                                </div>
                                <div class="col-auto">
                                    <form action="{{ route('admin.tools.maintenance.action', 'clear_sessions') }}" method="POST" onsubmit="return confirm('Bütün istifadəçilər sistemdən çıxarılacaq. Əminsiniz?');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Sıfırla</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
