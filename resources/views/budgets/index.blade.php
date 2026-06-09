@extends('layouts.app')
@section('title', 'Budget - FinanceApp')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Budget Planning</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#budgetModal"><i class="bi bi-plus-lg me-1"></i>Tambah</button>
</div>

<form method="GET" class="mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-md-3">
            <select name="period" class="form-select form-select-sm"><option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Bulanan</option><option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Tahunan</option></select>
        </div>
        <div class="col-md-3"><input type="month" name="month" class="form-control form-control-sm" value="{{ $month }}"></div>
        <div class="col-md-2"><button class="btn btn-sm btn-outline-secondary">Filter</button></div>
    </div>
</form>

<div class="row g-3">
    @forelse($budgets as $b)
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <small class="text-muted">{{ $b->category?->name ?? '-' }}</small>
                        <h6 class="fw-bold mb-0">{{ $b->name }}</h6>
                    </div>
                    <span class="badge {{ $b->is_over_budget ? 'bg-danger' : ($b->progress_percentage > 80 ? 'bg-warning' : 'bg-success') }}">
                        {{ $b->progress_percentage }}%
                    </span>
                </div>
                <div class="progress progress-thin mb-2">
                    <div class="progress-bar {{ $b->is_over_budget ? 'bg-danger' : ($b->progress_percentage > 80 ? 'bg-warning' : 'bg-success') }}"
                         style="width: {{ min($b->progress_percentage, 100) }}%"></div>
                </div>
                <div class="d-flex justify-content-between small">
                    <span>Rp {{ number_format($b->spent, 0, ',', '.') }}</span>
                    <span>Rp {{ number_format($b->amount, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <small class="{{ $b->is_over_budget ? 'text-danger' : 'text-success' }}">
                        Sisa: Rp {{ number_format($b->remaining_amount, 0, ',', '.') }}
                    </small>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item" onclick="editBudget({{ $b }})"><i class="bi bi-pencil me-2"></i>Edit</button></li>
                            <li><form action="{{ route('web.budgets.destroy', $b->id) }}" method="POST" class="delete-form"><button class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Hapus</button></form></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">Belum ada budget</div>
    @endforelse
</div>

<div class="modal fade" id="budgetModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow">
    <form method="POST" action="{{ route('web.budgets.store') }}">
        @csrf
        <div class="modal-header border-0"><h5 class="modal-title fw-bold">Tambah Budget</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Nama Budget</label><input type="text" name="name" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Kategori</label><select name="category_id" class="form-select" required><option value="">Pilih</option>
                @foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach</select></div>
            <div class="mb-3"><label class="form-label">Jumlah Budget</label><input type="number" name="amount" class="form-control" required min="1"></div>
            <div class="mb-3"><label class="form-label">Periode</label><select name="period" class="form-select" required><option value="monthly">Bulanan</option><option value="yearly">Tahunan</option></select></div>
            <div class="mb-0"><label class="form-label">Bulan (untuk bulanan)</label><input type="month" name="month" class="form-control" value="{{ $month }}"></div>
        </div>
        <div class="modal-footer border-0"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div></div>

<div class="modal fade" id="editBudgetModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow">
    <form id="editBudgetForm" method="POST">
        @csrf @method('PUT')
        <div class="modal-header border-0"><h5 class="modal-title fw-bold">Edit Budget</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Nama Budget</label><input type="text" name="name" id="editBudgetName" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Kategori</label><select name="category_id" id="editBudgetCat" class="form-select" required>@foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach</select></div>
            <div class="mb-3"><label class="form-label">Jumlah Budget</label><input type="number" name="amount" id="editBudgetAmt" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Periode</label><select name="period" id="editBudgetPeriod" class="form-select"><option value="monthly">Bulanan</option><option value="yearly">Tahunan</option></select></div>
            <div class="mb-0"><label class="form-label">Bulan</label><input type="month" name="month" id="editBudgetMonth" class="form-control"></div>
        </div>
        <div class="modal-footer border-0"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div></div>
@endsection

@push('scripts')
<script>
function editBudget(b) {
    document.getElementById('editBudgetForm').action = '/budgets/' + b.id;
    document.getElementById('editBudgetName').value = b.name;
    document.getElementById('editBudgetCat').value = b.category_id;
    document.getElementById('editBudgetAmt').value = b.amount;
    document.getElementById('editBudgetPeriod').value = b.period;
    document.getElementById('editBudgetMonth').value = b.month ?? '';
    new bootstrap.Modal(document.getElementById('editBudgetModal')).show();
}
</script>
@endpush
