@extends('layouts.app')
@section('title', 'Kategori - FinanceApp')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Kategori</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
        <i class="bi bi-plus-lg me-1"></i>Tambah
    </button>
</div>

<form method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Cari kategori..." value="{{ $search ?? '' }}">
        <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
        @if($search ?? false)
            <a href="{{ route('web.categories.index') }}" class="btn btn-outline-danger"><i class="bi bi-x"></i></a>
        @endif
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Nama Kategori</th><th>Dibuat</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($categories as $i => $cat)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><span class="fw-medium">{{ $cat->name }}</span></td>
                    <td><small class="text-muted">{{ $cat->created_at->format('d M Y') }}</small></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary me-1" onclick="editCategory({{ $cat->id }}, '{{ $cat->name }}')" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form action="{{ route('web.categories.destroy', $cat->id) }}" method="POST" class="d-inline delete-form">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada kategori</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="{{ route('web.categories.store') }}">
                @csrf
                <div class="modal-header border-0"><h5 class="modal-title fw-bold">Tambah Kategori</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Makanan" required>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="editCategoryForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-header border-0"><h5 class="modal-title fw-bold">Edit Kategori</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="name" id="editCategoryName" class="form-control" required>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editCategory(id, name) {
    document.getElementById('editCategoryForm').action = '/categories/' + id;
    document.getElementById('editCategoryName').value = name;
    new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
}
</script>
@endpush
