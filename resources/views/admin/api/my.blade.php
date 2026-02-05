@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">API İnteqrasiyaları</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                <button class="nav-link active mb-2 text-start" data-bs-toggle="pill" data-bs-target="#cat-payment" type="button">
                    <i class="fas fa-credit-card me-2"></i> Ödəniş Sistemləri
                </button>
                <button class="nav-link mb-2 text-start" data-bs-toggle="pill" data-bs-target="#cat-map" type="button">
                    <i class="fas fa-map-marked-alt me-2"></i> Xəritə və Naviqasiya
                </button>
                <button class="nav-link mb-2 text-start" data-bs-toggle="pill" data-bs-target="#cat-auth" type="button">
                    <i class="fas fa-users me-2"></i> Sosial Giriş
                </button>
                <button class="nav-link mb-2 text-start" data-bs-toggle="pill" data-bs-target="#cat-security" type="button">
                    <i class="fas fa-shield-alt me-2"></i> Təhlükəsizlik
                </button>
                <button class="nav-link mb-2 text-start" data-bs-toggle="pill" data-bs-target="#cat-other" type="button">
                    <i class="fas fa-plug me-2"></i> Digər
                </button>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content" id="v-pills-tabContent">
                @foreach(['payment', 'map', 'auth', 'security', 'other'] as $index => $cat)
                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="cat-{{ $cat }}">

                        @if(isset($apis[$cat]))
                            @foreach($apis[$cat] as $api)
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            <i class="{{ $api->logo }} me-2"></i> {{ $api->name }}
                                        </h6>

                                        <!-- Status Badge -->
                                        @if($api->status)
                                            <span class="badge bg-success">Aktiv</span>
                                        @else
                                            <span class="badge bg-secondary">Deaktiv</span>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('admin.api.update', $api->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="row">
                                                @foreach($api->credentials as $key => $value)
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label text-capitalize small text-muted">
                                                            {{ str_replace('_', ' ', $key) }}
                                                        </label>
                                                        <input type="text" class="form-control" name="credentials[{{ $key }}]" value="{{ $value }}" placeholder="...">
                                                    </div>
                                                @endforeach
                                            </div>

                                            <hr>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="status" value="1" id="status_{{ $api->id }}" {{ $api->status ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="status_{{ $api->id }}">Servisi Aktivləşdir</label>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-sm px-4">
                                                    <i class="fas fa-save me-1"></i> Yadda Saxla
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-info">Bu kateqoriyada API mövcud deyil.</div>
                        @endif

                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
