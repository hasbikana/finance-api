@extends('layouts.app')
@section('title', 'Laporan Bulanan - FinanceApp')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Laporan Bulanan</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('web.reports.exportPdf', ['month' => $month]) }}" class="btn btn-outline-danger"><i class="bi bi-file-pdf me-1"></i>PDF</a>
        <a href="{{ route('web.reports.exportCsv', ['month' => $month]) }}" class="btn btn-outline-secondary"><i class="bi bi-download me-1"></i>CSV</a>
    </div>
</div>

<form method="GET" class="mb-4">
    <div class="row g-2 align-items-end">
        <div class="col-md-3"><input type="month" name="month" class="form-control" value="{{ $month }}"></div>
        <div class="col-md-2"><button class="btn btn-outline-secondary">Filter</button></div>
    </div>
</form>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card card-stat p-3">
            <small class="text-muted">Total Income</small>
            <h5 class="fw-bold text-success mb-0">Rp {{ number_format($data['total_income'] ?? 0, 0, ',', '.') }}</h5>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-stat p-3">
            <small class="text-muted">Total Expense</small>
            <h5 class="fw-bold text-danger mb-0">Rp {{ number_format($data['total_expense'] ?? 0, 0, ',', '.') }}</h5>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-stat p-3">
            <small class="text-muted">Balance</small>
            <h5 class="fw-bold mb-0">Rp {{ number_format($data['balance'] ?? 0, 0, ',', '.') }}</h5>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-stat p-3">
            <small class="text-muted">Total Transaksi</small>
            <h5 class="fw-bold mb-0">{{ $data['transaction_count'] ?? 0 }}</h5>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0"><h6 class="fw-bold mb-0">Daftar Transaksi</h6></div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Tanggal</th><th>Tipe</th><th>Kategori</th><th>Jumlah</th><th>Deskripsi</th></tr></thead>
                    <tbody>
                        @forelse($data['transactions'] ?? [] as $t)
                        <tr>
                            <td><small>{{ $t->date instanceof \Carbon\Carbon ? $t->date->format('d M Y') : $t->date }}</small></td>
                            <td><span class="badge {{ $t->type === 'income' ? 'bg-success' : 'bg-danger' }}">{{ $t->type }}</span></td>
                            <td>{{ $t->category?->name ?? '-' }}</td>
                            <td class="fw-bold {{ $t->type === 'income' ? 'text-success' : 'text-danger' }}">Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                            <td><small>{{ Str::limit($t->description, 25) }}</small></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-3 text-muted">Tidak ada transaksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0"><h6 class="fw-bold mb-0">Expense by Category</h6></div>
            <div class="card-body"><canvas id="expenseChart" height="250"></canvas></div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0"><h6 class="fw-bold mb-0">Income by Category</h6></div>
            <div class="card-body"><canvas id="incomeChart" height="250"></canvas></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const expenseData = @json($data['expense_by_category'] ?? []);
    const incomeData = @json($data['income_by_category'] ?? []);

    new Chart(document.getElementById('expenseChart'), {
        type: 'doughnut',
        data: {
            labels: expenseData.map(d => d.category_name),
            datasets: [{ data: expenseData.map(d => d.total), backgroundColor: ['#ef4444','#f59e0b','#3b82f6','#8b5cf6','#ec4899','#06b6d4','#84cc16','#f97316'] }]
        },
        options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } } }
    });

    new Chart(document.getElementById('incomeChart'), {
        type: 'doughnut',
        data: {
            labels: incomeData.map(d => d.category_name),
            datasets: [{ data: incomeData.map(d => d.total), backgroundColor: ['#22C55E','#0F766E','#14B8A6','#10b981','#34d399','#6ee7b7','#a7f3d0','#d1fae5'] }]
        },
        options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } } }
    });
});
</script>
@endpush
