@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-language text-primary"></i> Tərcümə: {{ $language->name }}</h3>
        <a href="{{ route('languages.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Geri</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('languages.updateTranslate', $language->id) }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="translateTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 45%">Açar Söz (Key)</th>
                                <th style="width: 45%">Tərcümə (Value)</th>
                                <th style="width: 10%">Sil</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($translations as $key => $value)
                            <tr>
                                <td>
                                    <input type="text" name="key[]" class="form-control bg-light" value="{{ $key }}" readonly>
                                </td>
                                <td>
                                    <input type="text" name="value[]" class="form-control" value="{{ $value }}">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Hələ heç bir tərcümə yoxdur. Aşağıdan əlavə edin.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-success" id="addRow"><i class="fas fa-plus"></i> Yeni Sətir</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Yadda Saxla</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Yeni sətir əlavə etmək
        document.getElementById('addRow').addEventListener('click', function() {
            var table = document.getElementById('translateTable').getElementsByTagName('tbody')[0];
            var newRow = table.insertRow();

            var cell1 = newRow.insertCell(0);
            var cell2 = newRow.insertCell(1);
            var cell3 = newRow.insertCell(2);
            cell3.className = "text-center";

            cell1.innerHTML = '<input type="text" name="key[]" class="form-control" placeholder="Məs: welcome_message">';
            cell2.innerHTML = '<input type="text" name="value[]" class="form-control" placeholder="Tərcüməsi">';
            cell3.innerHTML = '<button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>';
        });

        // Sətiri silmək (Delegation)
        document.getElementById('translateTable').addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('tr').remove();
            }
        });
    });
</script>
@endsection
