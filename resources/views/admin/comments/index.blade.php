@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">{{ $pageTitle }}</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th width="200">İstifadəçi</th>
                            <th>Rəy / Obyekt</th>
                            <th width="120" class="text-center">Reytinq</th>
                            <th width="100" class="text-center">Status</th>
                            <th class="text-end pe-4">Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comments as $comment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-bold">{{ $comment->name }}</div>
                                <div class="small text-muted">{{ $comment->email }}</div>
                                <div class="small text-muted mt-1"><i class="far fa-clock me-1"></i> {{ $comment->created_at->format('d.m.Y H:i') }}</div>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <i class="fas fa-quote-left text-muted me-1 small"></i>
                                    {{ Str::limit($comment->content, 80) }}
                                </div>

                                <!-- Obyektin adı (Həkim, Məhsul və s.) -->
                                @if($comment->commentable)
                                    <div class="badge bg-light text-dark border mt-1">
                                        @if($comment->commentable_type == 'App\Models\Doctor')
                                            <i class="fas fa-user-md me-1 text-primary"></i> Dr. {{ $comment->commentable->full_name ?? 'Naməlum' }}
                                        @elseif($comment->commentable_type == 'App\Models\Product')
                                            <i class="fas fa-box me-1 text-success"></i> {{ $comment->commentable->getTranslation('name', app()->getLocale(), false) ?? 'Məhsul' }}
                                        @elseif($comment->commentable_type == 'App\Models\Post')
                                            <i class="fas fa-newspaper me-1 text-info"></i> {{ $comment->commentable->getTranslation('title', app()->getLocale(), false) ?? 'Məqalə' }}
                                        @endif
                                    </div>
                                @else
                                    <span class="text-danger small">Obyekt silinib</span>
                                @endif

                                <!-- Cavab varsa göstər -->
                                @if($comment->replies->count() > 0)
                                    <div class="mt-2 ps-3 border-start border-3 border-info">
                                        <small class="text-info fw-bold">Admin Cavabı:</small>
                                        <p class="small mb-0 text-muted">{{ Str::limit($comment->replies->first()->content, 50) }}</p>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($comment->rating)
                                    <div class="text-warning small">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $comment->rating ? 'fas' : 'far' }} fa-star"></i>
                                        @endfor
                                    </div>
                                    <div class="small fw-bold">{{ $comment->rating }}/5</div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($comment->is_approved)
                                    <span class="badge bg-success">Təsdiqlənib</span>
                                @else
                                    <span class="badge bg-warning text-dark">Gözləyir</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <!-- Cavab Yaz -->
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#replyModal{{ $comment->id }}"
                                            title="Cavab Yaz">
                                        <i class="fas fa-reply"></i>
                                    </button>

                                    <!-- Status Dəyiş -->
                                    @if($comment->is_approved)
                                        <form action="{{ route('admin.comments.status', $comment->id) }}" method="POST" class="d-inline">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="0">
                                            <button class="btn btn-sm btn-outline-warning" title="Gizlət"><i class="fas fa-eye-slash"></i></button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.comments.status', $comment->id) }}" method="POST" class="d-inline">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="1">
                                            <button class="btn btn-sm btn-outline-success" title="Təsdiqlə"><i class="fas fa-check"></i></button>
                                        </form>
                                    @endif

                                    <!-- Sil -->
                                    <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmək istədiyinizə əminsiniz?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Sil"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Reply Modal -->
                        <div class="modal fade" id="replyModal{{ $comment->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.comments.reply') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Cavab Yaz: {{ $comment->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3 p-3 bg-light rounded border">
                                                <small class="text-muted d-block mb-1">İstifadəçi rəyi:</small>
                                                <i>"{{ $comment->content }}"</i>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Sizin Cavabınız</label>
                                                <textarea name="content" class="form-control" rows="4" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bağla</button>
                                            <button type="submit" class="btn btn-primary">Göndər</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Şərh yoxdur.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $comments->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
