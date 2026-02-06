@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Ehtiyat Nüsxələr (Backups)</h3>
        <div>
            <!-- Standart DB Backup -->
            <form action="{{ route('admin.system.backups.store') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="type" value="db">
                <button type="submit" class="btn btn-primary shadow-sm me-2">
                    <i class="fas fa-database fa-sm text-white-50 me-1"></i> Verilənlər Bazası (SQL)
                </button>
            </form>

            <!-- Tam Backup -->
            <form action="{{ route('admin.system.backups.store') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="type" value="full">
                <button type="submit" class="btn btn-warning shadow-sm" onclick="return confirm('Tam backup almaq vaxt ala bilər. Davam etmək istəyirsiniz?');">
                    <i class="fas fa-file-archive fa-sm text-white-50 me-1"></i> Tam Backup (Sayt + SQL)
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">Mövcud Nüsxələr</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Fayl Adı</th>
                            <th>Tip</th>
                            <th>Ölçü</th>
                            <th>Tarix</th>
                            <th class="text-end pe-4">Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($backups as $index => $backup)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if(str_ends_with($backup['name'], '.zip'))
                                    <i class="fas fa-file-archive text-warning me-2"></i>
                                @else
                                    <i class="fas fa-database text-primary me-2"></i>
                                @endif
                                <span class="font-monospace">{{ $backup['name'] }}</span>
                            </td>
                            <td>
                                @if(str_ends_with($backup['name'], '.zip'))
                                    <span class="badge bg-warning text-dark">Tam Sistem</span>
                                @else
                                    <span class="badge bg-primary">Database</span>
                                @endif
                            </td>
                            <td>{{ $backup['size'] }}</td>
                            <td>{{ $backup['date'] }}</td>
                            <td class="text-end pe-4">
                                <!-- Geri Yüklə (Yalnız SQL üçün) -->
                                @if(str_ends_with($backup['name'], '.sql'))
                                    <form action="{{ route('admin.system.backups.restore', $backup['name']) }}" method="POST" class="d-inline" onsubmit="return confirm('DİQQƏT! Bu nüsxəni geri yükləmək mövcud bazanı silib bu versiyanı yazacaq. Əminsiniz?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-info text-white me-1" title="Geri Yüklə (Restore)">
                                            <i class="fas fa-history"></i>
                                        </button>
                                    </form>
                                @endif

                                <!-- Yüklə (Download) -->
                                <a href="{{ route('admin.system.backups.download', $backup['name']) }}" class="btn btn-sm btn-success me-1" title="Yüklə">
                                    <i class="fas fa-download"></i>
                                </a>

                                <!-- Sil -->
                                <form action="{{ route('admin.system.backups.destroy', $backup['name']) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu ehtiyat nüsxəni silmək istədiyinizə əminsiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Sil">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-database fa-3x mb-3 text-gray-300"></i>
                                <p class="mb-0">Hələ heç bir ehtiyat nüsxə yaradılmayıb.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
