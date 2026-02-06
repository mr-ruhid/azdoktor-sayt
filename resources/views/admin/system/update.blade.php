@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Sistem Yeniləməsi</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Yeniləmə Paketini Yüklə</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Diqqət!</strong> Yeniləmə etməzdən əvvəl mütləq "Ehtiyat Nüsxə" (Backup) alın.
                        Yanlış paket yükləmək sistemi sıradan çıxara bilər.
                    </div>

                    <form action="{{ route('admin.system.update.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Yeniləmə Faylı (.zip)</label>
                            <input type="file" name="update_file" class="form-control" accept=".zip" required>
                            <small class="text-muted">Maksimum fayl ölçüsü: 50MB</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Yeniləməni başlatmaq istədiyinizə əminsiniz? Bu əməliyyat bir neçə dəqiqə çəkə bilər.')">
                                <i class="fas fa-sync-alt me-1"></i> Yeniləməni Başlat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4 border-left-info">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-info">Təlimat</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0 ps-3">
                        <li class="mb-2">Yeniləmə faylı mütləq <strong>.zip</strong> formatında olmalıdır.</li>
                        <li class="mb-2">Zip faylının içindəki qovluq strukturu layihənin kök qovluğu ilə eyni olmalıdır (məsələn: <code>app/</code>, <code>resources/</code> və s.).</li>
                        <li class="mb-2">Sistem avtomatik olaraq faylları əvəzləyəcək və verilənlər bazası miqrasiyalarını işlədəcək.</li>
                        <li>Yeniləmə bitdikdən sonra keşlər avtomatik təmizlənəcək.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
