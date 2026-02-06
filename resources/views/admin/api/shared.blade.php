@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Paylaşılan API-lər (Mobil Tətbiq Üçün)</h3>
    </div>

    <div class="alert alert-info border-left-info shadow-sm">
        <i class="fas fa-info-circle me-2"></i>
        Bu bölmədəki endpointlər saytın məlumatlarını <strong>.json</strong> formatında mobil tətbiqlərə ötürmək üçün istifadə olunur.
        <br><strong>Base URL:</strong> <code>{{ url('/api') }}</code>
    </div>

    <div class="row">
        <!-- Auth -->
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-white border-bottom-0">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-key me-2"></i> Autentifikasiya (Giriş/Qeydiyyat)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Metod</th>
                                    <th>Endpoint</th>
                                    <th>Təsvir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-success">POST</span></td>
                                    <td><code>/login</code></td>
                                    <td>Email və Şifrə ilə giriş. Token qaytarır.</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">POST</span></td>
                                    <td><code>/register</code></td>
                                    <td>Yeni istifadəçi qeydiyyatı.</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">GET</span></td>
                                    <td><code>/user</code></td>
                                    <td>İstifadəçi profil məlumatları (Token tələb edir).</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Məlumatlar -->
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-white border-bottom-0">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-database me-2"></i> Məlumat Bazası (Həkim Axtarış App)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Metod</th>
                                    <th>Endpoint</th>
                                    <th>Parametrlər</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-primary">GET</span></td>
                                    <td><code>/doctors</code></td>
                                    <td><code>?search=ad</code>, <code>?clinic_id=1</code></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">GET</span></td>
                                    <td><code>/doctors/{id}</code></td>
                                    <td>Həkimin ID-si</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">GET</span></td>
                                    <td><code>/clinics</code></td>
                                    <td>Bütün klinikalar</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">GET</span></td>
                                    <td><code>/specialties</code></td>
                                    <td>Bütün ixtisaslar</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Əməliyyatlar -->
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header py-3 bg-white border-bottom-0">
                    <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-bolt me-2"></i> Əməliyyatlar (Token Tələb Edir)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Metod</th>
                                    <th>Endpoint</th>
                                    <th>Body (JSON)</th>
                                    <th>Nəticə</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-success">POST</span></td>
                                    <td><code>/reservations</code></td>
                                    <td>
                                        <pre class="mb-0 small bg-light p-1 rounded">{ "doctor_id": 1, "date": "2024-05-20", "time": "14:00" }</pre>
                                    </td>
                                    <td>Admin panelin "Rezervasiyalar" bölməsinə düşür.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
