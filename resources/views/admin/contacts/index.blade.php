@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Əlaqə Mesajları</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Ad Soyad</th>
                            <th>Mövzu</th>
                            <th>Tarix</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contacts as $contact)
                        <tr class="{{ !$contact->is_read ? 'table-active fw-bold' : '' }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div>{{ $contact->full_name }}</div>
                                <div class="small text-muted">{{ $contact->email }}</div>
                            </td>
                            <td>{{ Str::limit($contact->subject, 40) }}</td>
                            <td>{{ $contact->created_at->format('d.m.Y H:i') }}</td>
                            <td class="text-center">
                                @if($contact->is_replied)
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Cavablandı</span>
                                @elseif($contact->is_read)
                                    <span class="badge bg-info text-dark">Oxundu</span>
                                @else
                                    <span class="badge bg-warning text-dark">Yeni</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-sm btn-primary me-1">
                                    <i class="fas fa-eye"></i> Bax
                                </a>
                                <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmək istədiyinizə əminsiniz?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Heç bir mesaj yoxdur.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3 d-flex justify-content-center">
                {{ $contacts->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
