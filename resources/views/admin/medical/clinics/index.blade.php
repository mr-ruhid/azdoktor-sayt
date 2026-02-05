@extends('admin.layout')

@section('content')

{{-- YANDEX MAP API SCRIPT --}}
@if(!empty($yandexApiKey))
    <script src="https://api-maps.yandex.ru/2.1/?apikey={{ $yandexApiKey }}&lang={{ app()->getLocale() == 'az' ? 'az_AZ' : 'en_US' }}" type="text/javascript"></script>
@endif

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Klinikalar</h3>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createClinicModal">
            <i class="fas fa-plus me-1"></i> Yeni Klinika
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <!-- Axtarış hissəsi -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Klinika adı axtar...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th width="80">Loqo</th>
                            <th>Klinika Adı</th>
                            <th>Ünvan</th>
                            <th>Telefon</th>
                            <th>Status</th>
                            <th class="text-end">Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clinics as $clinic)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if ($clinic->image)
                                        <img src="{{ asset($clinic->image) }}" class="rounded border bg-light p-1" width="50" height="50" style="object-fit: contain;">
                                    @else
                                        <div class="rounded border bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-hospital text-secondary"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $clinic->name }}</div>
                                </td>
                                <td>
                                    <small class="text-muted">{{ Str::limit($clinic->address, 30) }}</small>
                                </td>
                                <td>{{ $clinic->phone }}</td>
                                <td>
                                    @if ($clinic->status)
                                        <span class="badge bg-success bg-opacity-10 text-success">Aktiv</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger">Passiv</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editClinicModal{{ $clinic->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.clinics.destroy', $clinic->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmək istədiyinizə əminsiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal (Hər sətir üçün) -->
                            <div class="modal fade" id="editClinicModal{{ $clinic->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.clinics.update', $clinic->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title">Redaktə Et: {{ $clinic->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                @include('admin.medical.clinics.form', ['clinic' => $clinic])
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bağla</button>
                                                <button type="submit" class="btn btn-primary">Yadda Saxla</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Hələ heç bir klinika yoxdur.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $clinics->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createClinicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('admin.clinics.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Yeni Klinika Əlavə Et</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('admin.medical.clinics.form', ['clinic' => null])
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ləğv et</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Yadda Saxla
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Yandex Map Logic --}}
@if(!empty($yandexApiKey))
<script type="text/javascript">
    var clinicMap = null;
    var placemark = null;

    function initMap(lat, lng, mapId) {
        // Əgər xəritə artıq varsa, təmizlə
        if (clinicMap) { clinicMap.destroy(); clinicMap = null; }

        var defaultCoords = [40.4093, 49.8671]; // Bakı
        var center = (lat && lng) ? [lat, lng] : defaultCoords;

        ymaps.ready(function() {
            clinicMap = new ymaps.Map(mapId, {
                center: center,
                zoom: 12,
                controls: ['zoomControl', 'searchControl']
            });

            // Mövcud koordinat varsa marker qoy
            if (lat && lng) {
                placemark = new ymaps.Placemark(center, {}, { draggable: true });
                clinicMap.geoObjects.add(placemark);
            }

            clinicMap.events.add('click', function (e) {
                var coords = e.get('coords');
                setPlacemark(coords);
            });
        });

        function setPlacemark(coords) {
            if (placemark) {
                placemark.geometry.setCoordinates(coords);
            } else {
                placemark = new ymaps.Placemark(coords, {}, { draggable: true });
                clinicMap.geoObjects.add(placemark);

                placemark.events.add('dragend', function (e) {
                    var newCoords = placemark.geometry.getCoordinates();
                    updateInputs(newCoords, mapId);
                });
            }
            updateInputs(coords, mapId);
        }

        function updateInputs(coords, mapId) {
            // mapId-dən asılı olaraq inputları tapmaq lazımdır (Create və Edit üçün fərqli olacaq)
            // Sadəlik üçün bu nümunədə əsas inputları tapırıq, real layihədə ID-ləri dinamik etmək lazımdır
            var container = document.getElementById(mapId).closest('.modal-body');
            container.querySelector('input[name="latitude"]').value = coords[0].toPrecision(6);
            container.querySelector('input[name="longitude"]').value = coords[1].toPrecision(6);
        }
    }

    // Modal açılanda xəritəni işə sal
    var createModal = document.getElementById('createClinicModal');
    createModal.addEventListener('shown.bs.modal', function () {
        initMap(null, null, 'createMap');
    });
</script>
@endif

@endsection
