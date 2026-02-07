@extends('layouts.public')

@section('content')
<div class="container py-4">
    <h1 class="text-center fw-bold mb-5">{{ $page->title }}</h1>

    <div class="row">
        <!-- Əlaqə Formu -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0 p-4">
                <h4 class="mb-4">Bizə yazın</h4>
                <form action="#" method="POST"> <!-- Route sonra əlavə ediləcək -->
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Adınız</label>
                            <input type="text" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Soyadınız</label>
                            <input type="text" class="form-control bg-light border-0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-poçt</label>
                        <input type="email" class="form-control bg-light border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mesajınız</label>
                        <textarea class="form-control bg-light border-0" rows="5" required></textarea>
                    </div>
                    <button class="btn btn-primary px-5 rounded-pill">Göndər</button>
                </form>
            </div>
        </div>

        <!-- Əlaqə Məlumatları -->
        <div class="col-md-5">
            <div class="ps-md-4 mt-4 mt-md-0">
                <div class="mb-4">
                    <h5><i class="fas fa-map-marker-alt text-primary me-2"></i> Ünvan</h5>
                    <p class="text-muted">Bakı şəhəri, Səbail rayonu...</p>
                </div>
                <div class="mb-4">
                    <h5><i class="fas fa-envelope text-primary me-2"></i> E-poçt</h5>
                    <p class="text-muted">{{ $settings->mail_from_address ?? 'info@azdoktor.com' }}</p>
                </div>
                <div class="mb-4">
                    <h5><i class="fas fa-phone text-primary me-2"></i> Telefon</h5>
                    <p class="text-muted">+994 50 000 00 00</p>
                </div>

                <!-- Sosial Media (Sənin istədiyin edit olunan hissə) -->
                <div class="mt-5">
                    <h5>Sosial Media</h5>
                    <div class="d-flex gap-3 mt-3">
                        {{-- Bu linkləri gələcəkdə Settings cədvəlinə əlavə edib ordan çəkə bilərik --}}
                        <a href="#" class="btn btn-outline-primary rounded-circle"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-outline-danger rounded-circle"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn btn-outline-info rounded-circle"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-outline-success rounded-circle"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
