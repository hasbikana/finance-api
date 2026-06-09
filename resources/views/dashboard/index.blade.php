@extends('layouts.app')
@section('title', 'Dashboard - FinanceApp')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Dashboard</h4>
        <p class="text-muted small mb-0">{{ $summary['current_month'] ?? '' }}</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#quickTransactionModal">
        <i class="bi bi-plus-lg me-1"></i> Transaksi Cepat
    </button>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-wallet2"></i></div>
                <div>
                    <small class="text-muted">Total Balance</small>
                    <h5 class="fw-bold mb-0">Rp {{ number_format($summary['balance'] ?? 0, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="icon bg-success bg-opacity-10 text-success"><i class="bi bi-arrow-down-circle"></i></div>
                <div>
                    <small class="text-muted">Income Bulan Ini</small>
                    <h5 class="fw-bold mb-0">Rp {{ number_format($summary['this_month_income'] ?? 0, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-arrow-up-circle"></i></div>
                <div>
                    <small class="text-muted">Expense Bulan Ini</small>
                    <h5 class="fw-bold mb-0">Rp {{ number_format($summary['this_month_expense'] ?? 0, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="icon bg-info bg-opacity-10 text-info"><i class="bi bi-cash-stack"></i></div>
                <div>
                    <small class="text-muted">Sisa Saldo</small>
                    <h5 class="fw-bold mb-0">Rp {{ number_format($summary['this_month_balance'] ?? 0, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="fw-bold mb-0">Income vs Expense</h6>
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="280"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Transaksi Terbaru</h6>
                <a href="{{ route('web.transactions.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($recentTransactions as $t)
                <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
                    <div>
                        <div class="fw-medium small">{{ $t->category?->name ?? 'Unknown' }}</div>
                        <small class="text-muted">{{ $t->date->format('d M Y') }}</small>
                    </div>
                    <span class="fw-bold {{ $t->type === 'income' ? 'text-success' : 'text-danger' }}">
                        {{ $t->type === 'income' ? '+' : '-' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
                    </span>
                </div>
                @empty
                <div class="text-center py-4 text-muted">Belum ada transaksi</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="quickTransactionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="{{ route('web.transactions.store') }}">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Transaksi Cepat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small">Tipe</label>
                        <div class="d-flex gap-2">
                            <input type="radio" class="btn-check" name="type" id="typeExpense" value="expense" checked>
                            <label class="btn btn-outline-danger flex-fill" for="typeExpense"><i class="bi bi-arrow-up-circle me-1"></i>Pengeluaran</label>
                            <input type="radio" class="btn-check" name="type" id="typeIncome" value="income">
                            <label class="btn btn-outline-success flex-fill" for="typeIncome"><i class="bi bi-arrow-down-circle me-1"></i>Pemasukan</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Jumlah</label>
                        <input type="number" name="amount" class="form-control" placeholder="0" required min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Kategori</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Wallet</label>
                        <select name="wallet_id" class="form-select">
                            <option value="">Tanpa Wallet</option>
                            @foreach($wallets as $w)
                            <option value="{{ $w->id }}">{{ $w->name }} (Rp {{ number_format($w->balance, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Tanggal</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-0">
                        <label class="form-label small">Deskripsi</label>
                        <input type="text" name="description" class="form-control" placeholder="Opsional">
                    </div>
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
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('trendChart').getContext('2d');
    const trend = @json($monthlyTrend ?? []);
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: trend.map(t => t.month),
            datasets: [
                { label: 'Income', data: trend.map(t => t.income), backgroundColor: '#22C55E', borderRadius: 6 },
                { label: 'Expense', data: trend.map(t => t.expense), backgroundColor: '#ef4444', borderRadius: 6 }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + (v/1000).toFixed(0) + 'K' } } }
        }
    });
});
</script>
@endpush
