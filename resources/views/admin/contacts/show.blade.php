@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Mesaj Detalları</h3>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Mesajın Özü -->
        <div class="col-md-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Göndərən Məlumatları</h6>
                    <span class="text-muted small">{{ $contact->created_at->format('d.m.Y H:i') }}</span>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Ad Soyad:</th>
                            <td>{{ $contact->full_name }}</td>
                        </tr>
                        <tr>
                            <th>E-poçt:</th>
                            <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                        </tr>
                        <tr>
                            <th>Nömrə:</th>
                            <td><a href="tel:{{ $contact->phone }}">{{ $contact->phone ?? '-' }}</a></td>
                        </tr>
                        <tr>
                            <th>Mövzu:</th>
                            <td class="fw-bold">{{ $contact->subject }}</td>
                        </tr>
                    </table>
                    <hr>
                    <h6 class="fw-bold">Mesaj:</h6>
                    <div class="p-3 bg-light rounded border">
                        {!! nl2br(e($contact->message)) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Cavab Formu -->
        <div class="col-md-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-success">Cavab Göndər (E-poçt)</h6>
                </div>
                <div class="card-body">
                    @if($contact->is_replied)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-1"></i> Bu mesaja artıq cavab verilib.
                        </div>
                    @endif

                    <form action="{{ route('admin.contacts.reply', $contact->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Alıcı</label>
                            <input type="text" class="form-control" value="{{ $contact->email }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cavab Mətni <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="6" required placeholder="Hörmətli istifadəçi..."></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Göndər
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
