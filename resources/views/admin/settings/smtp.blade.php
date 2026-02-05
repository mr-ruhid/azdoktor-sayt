@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">SMTP (E-poçt) Tənzimləmələri</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">Server Məlumatları</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.smtp.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Mailer</label>
                        <input type="text" class="form-control" name="mail_mailer" value="{{ $setting->mail_mailer ?? 'smtp' }}">
                        <div class="form-text">Adətən: smtp</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Host</label>
                        <input type="text" class="form-control" name="mail_host" value="{{ $setting->mail_host }}" placeholder="smtp.gmail.com">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Port</label>
                        <input type="text" class="form-control" name="mail_port" value="{{ $setting->mail_port }}" placeholder="587">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Username (Email)</label>
                        <input type="text" class="form-control" name="mail_username" value="{{ $setting->mail_username }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="mail_password" value="{{ $setting->mail_password }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Encryption</label>
                        <input type="text" class="form-control" name="mail_encryption" value="{{ $setting->mail_encryption }}" placeholder="tls">
                        <div class="form-text">tls və ya ssl</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">From Address (Göndərən Email)</label>
                        <input type="text" class="form-control" name="mail_from_address" value="{{ $setting->mail_from_address }}" placeholder="info@azdoktor.com">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">From Name (Göndərən Adı)</label>
                        <input type="text" class="form-control" name="mail_from_name" value="{{ $setting->mail_from_name }}" placeholder="AzDoktor">
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="fas fa-save me-1"></i> Yadda Saxla
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="fas fa-info-circle me-1"></i>
        <strong>Qeyd:</strong> Gmail istifadə edirsinizsə, "App Password" yaratmalısınız. Standart şifrəniz işləməyəcək.
    </div>
</div>
@endsection
