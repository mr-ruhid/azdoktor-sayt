@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Media Kitabxanası</h3>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fas fa-upload fa-sm text-white-50"></i> Yeni Yüklə
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                @forelse($files as $file)
                    <div class="col-xl-2 col-md-3 col-sm-4 col-6 mb-4">
                        <div class="card h-100 shadow-sm file-card group-hover border">
                            <!-- Şəkil və ya İkon -->
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light position-relative" style="height: 150px; overflow: hidden;">
                                @if(Str::startsWith($file->file_type, 'image/'))
                                    <img src="{{ asset($file->file_path) }}" class="img-fluid" style="object-fit: cover; height: 100%; width: 100%;" alt="{{ $file->file_name }}">
                                @else
                                    <div class="text-center">
                                        <i class="fas fa-file-alt fa-3x text-secondary mb-2"></i>
                                        <span class="d-block small text-muted">{{ strtoupper(pathinfo($file->file_name, PATHINFO_EXTENSION)) }}</span>
                                    </div>
                                @endif

                                <!-- Hover Actions (Üstünə gələndə çıxan düymələr) -->
                                <div class="file-actions position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-dark bg-opacity-75 opacity-0 hover-opacity-100 transition">
                                    <button class="btn btn-light btn-sm me-2 copy-btn" data-url="{{ asset($file->file_path) }}" title="Linki Kopyala">
                                        <i class="fas fa-link"></i>
                                    </button>
                                    <form action="{{ route('admin.media.destroy', $file->id) }}" method="POST" onsubmit="return confirm('Bu faylı silmək istədiyinizə əminsiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="card-footer bg-white p-2 border-top-0">
                                <p class="small text-truncate mb-0 fw-bold" title="{{ $file->file_name }}">{{ $file->file_name }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <small class="text-muted" style="font-size: 10px;">{{ $file->size_formatted }}</small>
                                    <small class="text-muted" style="font-size: 10px;">{{ $file->created_at->format('d.m.Y') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-cloud-upload-alt fa-4x text-gray-300"></i>
                        </div>
                        <h5 class="text-gray-800">Media Kitabxanası Boşdur</h5>
                        <p class="text-muted">Yuxarıdakı "Yeni Yüklə" düyməsindən istifadə edərək ilk faylınızı yükləyin.</p>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $files->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal (Yükləmə Pəncərəsi) -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Yeni Fayl Yüklə</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Fayl Seçin</label>
                        <input type="file" name="file" class="form-control" required>
                        <div class="form-text">Maksimum ölçü: 10MB. Şəkillər (jpg, png, webp) və sənədlər (pdf) dəstəklənir.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bağla</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-cloud-upload-alt me-1"></i> Yüklə</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Hover effektləri üçün CSS */
    .transition { transition: all 0.3s ease; }
    .opacity-0 { opacity: 0; }
    .group-hover:hover .hover-opacity-100 { opacity: 1; }
    .bg-opacity-75 { background-color: rgba(33, 37, 41, 0.75) !important; }
    .file-card { transition: transform 0.2s; }
    .file-card:hover { transform: translateY(-5px); border-color: #4e73df !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Linki Kopyalamaq Skripti
        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.getAttribute('data-url');

                // Clipboard API istifadə edirik
                navigator.clipboard.writeText(url).then(() => {
                    const originalIcon = this.innerHTML;

                    // Uğurlu olduğunu göstərmək üçün ikonu dəyişirik
                    this.innerHTML = '<i class="fas fa-check"></i>';
                    this.classList.remove('btn-light');
                    this.classList.add('btn-success');

                    // 1.5 saniyə sonra əvvəlki halına qaytarırıq
                    setTimeout(() => {
                        this.innerHTML = originalIcon;
                        this.classList.remove('btn-success');
                        this.classList.add('btn-light');
                    }, 1500);
                }).catch(err => {
                    console.error('Kopyalama xətası:', err);
                    alert('Linki kopyalamaq mümkün olmadı. Lütfən əllə kopyalayın.');
                });
            });
        });
    });
</script>
@endsection
