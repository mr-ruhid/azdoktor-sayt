@extends('layouts.public')

@section('title', $page->getTranslation('title', app()->getLocale()))

@section('content')

{{-- Hero Section --}}
<div class="bg-primary text-white py-5 position-relative overflow-hidden">
    <div class="container text-center position-relative z-2">
        <h1 class="fw-bold display-5 mb-3">{{ $page->getTranslation('title', app()->getLocale()) }}</h1>
        <div class="lead opacity-75">
            {!! $page->getTranslation('content', app()->getLocale()) !!}
        </div>
    </div>
    {{-- Dalğa --}}
    <div class="position-absolute bottom-0 start-0 w-100" style="line-height: 0;">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="width: 100%; height: 40px; fill: #f8f9fa;">
            <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="shape-fill"></path>
            <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,24.96,66.2,48.66,106,53.25,58.74,6.76,112.65-20.89,165-49.89,40-22.18,80.89-51.15,125.29-57.94,20-3,40.67-1.12,60.42,4.68V0Z" opacity=".5" class="shape-fill"></path>
            <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="shape-fill"></path>
        </svg>
    </div>
</div>

<div class="container py-5">

    {{-- 1. İxtisas Üzrə Qiymətlər --}}
    @php $specialties = $page->getMeta('specialties_list', []); @endphp
    @if(!empty($specialties))
        <div class="mb-5">
            <h3 class="fw-bold text-center mb-4 text-primary">{{ __('pricing.specialty_prices', ['default' => 'İxtisas üzrə Qiymətlər']) }}</h3>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3">{{ __('pricing.specialty', ['default' => 'İxtisas']) }}</th>
                                        <th class="pe-4 py-3 text-end">{{ __('pricing.price_yearly', ['default' => 'Qiymət (İllik)']) }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($specialties as $item)
                                        <tr>
                                            <td class="ps-4 fw-medium">{{ $item['name'][app()->getLocale()] ?? '-' }}</td>
                                            <td class="pe-4 text-end fw-bold text-primary">{{ $item['price'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- 2. Ümumi Paketlər --}}
    @php $packages = $page->getMeta('packages_list', []); @endphp
    @if(!empty($packages))
        <div class="mb-5">
            <h3 class="fw-bold text-center mb-4 text-warning">{{ __('pricing.general_packages', ['default' => 'Ümumi Paketlər və Korporativ']) }}</h3>
            <div class="row g-4 justify-content-center">
                @foreach($packages as $pkg)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm hover-shadow rounded-4 text-center">
                            <div class="card-body p-4 d-flex flex-column">
                                <h5 class="fw-bold text-dark mb-3">{{ $pkg['title'][app()->getLocale()] ?? '-' }}</h5>
                                <div class="mb-3 text-muted small flex-grow-1">
                                    {{ $pkg['description'][app()->getLocale()] ?? '' }}
                                </div>
                                <div class="mt-auto">
                                    <span class="display-6 fw-bold text-primary">{{ $pkg['price'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- 3. Alt Məlumat (HTML) --}}
    @php $bottomHtml = $page->getMeta('bottom_html')[app()->getLocale()] ?? ''; @endphp
    @if(!empty($bottomHtml))
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8">
                <div class="bg-light p-4 rounded-4 border">
                    {!! $bottomHtml !!}
                </div>
            </div>
        </div>
    @endif

</div>

{{-- Sonda Sabit Qeydiyyat Düyməsi (Artıq Fixed deyil, normal section-dır) --}}
<div class="bg-white border-top py-4 mt-5">
    <div class="container text-center">
        <span class="fw-bold d-block d-md-inline mb-2 mb-md-0 me-md-3">{{ __('pricing.want_to_join', ['default' => 'Həkim olaraq qoşulmaq istəyirsiniz?']) }}</span>
        <a href="{{ route('register.doctor') }}" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm pulse-btn">
            <i class="fas fa-user-md me-2"></i> {{ __('pricing.doctor_registration', ['default' => 'Həkim Qeydiyyatı']) }}
        </a>
    </div>
</div>

<style>
    .hover-shadow:hover { transform: translateY(-5px); transition: all 0.3s; box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    .pulse-btn { animation: pulse 2s infinite; }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(25, 135, 84, 0); }
        100% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
    }
</style>

@endsection
