@extends('layouts.app')
@section('title', 'Transaksi - FinanceApp')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Transaksi</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('web.transactions.exportCsv', request()->query()) }}" class="btn btn-outline-secondary">
            <i class="bi bi-download me-1"></i>Export CSV
        </a>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transactionModal">
            <i class="bi bi-plus-lg me-1"></i>Tambah
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small">Tipe</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="income" {{ ($filters['type'] ?? '') === 'income' ? 'selected' : '' }}>Income</option>
                    <option value="expense" {{ ($filters['type'] ?? '') === 'expense' ? 'selected' : '' }}>Expense</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Kategori</label>
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Wallet</label>
                <select name="wallet_id" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($wallets as $w)
                    <option value="{{ $w->id }}" {{ ($filters['wallet_id'] ?? '') == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $filters['start_date'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $filters['end_date'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Cari</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ $filters['search'] ?? '' }}">
                    <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0" id="transactionTable">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th><th>Tipe</th><th>Kategori</th><th>Wallet</th><th>Jumlah</th><th>Deskripsi</th><th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                <tr>
                    <td><small>{{ $t->date->format('d M Y') }}</small></td>
                    <td><span class="badge {{ $t->type === 'income' ? 'bg-success' : 'bg-danger' }}">{{ $t->type === 'income' ? 'Income' : 'Expense' }}</span></td>
                    <td>{{ $t->category?->name ?? '-' }}</td>
                    <td>{{ $t->wallet?->name ?? '-' }}</td>
                    <td class="fw-bold {{ $t->type === 'income' ? 'text-success' : 'text-danger' }}">
                        Rp {{ number_format($t->amount, 0, ',', '.') }}
                    </td>
                    <td><small class="text-muted">{{ Str::limit($t->description, 30) }}</small></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary me-1" onclick="editTransaction({{ $t }})"><i class="bi bi-pencil"></i></button>
                        <form action="{{ route('web.transactions.destroy', $t->id) }}" method="POST" class="d-inline delete-form">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transactions->hasPages())
    <div class="card-footer bg-white">{{ $transactions->appends($filters)->links() }}</div>
    @endif
</div>

@include('transactions._modal')
@include('transactions._edit_modal')
@endsection
