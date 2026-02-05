@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Yeni Həkim Yarat</h3>
        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Geri Qayıt
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.doctors.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- Ortaq form faylını çağırırıq --}}
        @include('admin.medical.doctors.form', ['doctor' => null])

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-5">
            <button type="submit" class="btn btn-success btn-lg px-5">
                <i class="fas fa-save me-1"></i> Yadda Saxla
            </button>
        </div>
    </form>
</div>
@endsection
