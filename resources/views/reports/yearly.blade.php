@extends('layouts.app')
@section('title', 'Laporan Tahunan - FinanceApp')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Laporan Tahunan</h4>
</div>

<form method="GET" class="mb-4">
    <div class="row g-2 align-items-end">
        <div class="col-md-2"><select name="year" class="form-select"><option value="">Pilih Tahun</option>
            @for($y = date('Y'); $y >= date('Y')-5; $y--)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select></div>
        <div class="col-md-2"><button class="btn btn-outline-secondary">Filter</button></div>
    </div>
</form>

@php
    $yearlyIncome = 0; $yearlyExpense = 0; $yearlyCount = 0;
    foreach($monthlySummaries as $s) { $yearlyIncome += $s['total_income'] ?? 0; $yearlyExpense += $s['total_expense'] ?? 0; $yearlyCount += $s['transaction_count'] ?? 0; }
@endphp

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="card card-stat p-3"><small class="text-muted">Total Income</small><h5 class="fw-bold text-success mb-0">Rp {{ number_format($yearlyIncome, 0, ',', '.') }}</h5></div></div>
    <div class="col-6 col-md-3"><div class="card card-stat p-3"><small class="text-muted">Total Expense</small><h5 class="fw-bold text-danger mb-0">Rp {{ number_format($yearlyExpense, 0, ',', '.') }}</h5></div></div>
    <div class="col-6 col-md-3"><div class="card card-stat p-3"><small class="text-muted">Balance</small><h5 class="fw-bold mb-0">Rp {{ number_format($yearlyIncome - $yearlyExpense, 0, ',', '.') }}</h5></div></div>
    <div class="col-6 col-md-3"><div class="card card-stat p-3"><small class="text-muted">Total Transaksi</small><h5 class="fw-bold mb-0">{{ $yearlyCount }}</h5></div></div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body"><canvas id="yearlyChart" height="300"></canvas></div>
</div>

<div class="row g-3">
    @foreach($monthlySummaries as $i => $ms)
    <div class="col-md-4 mb-2">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="fw-bold">{{ $ms['month_name'] ?? ('Month ' . ($i+1)) }}</small>
                        <div class="small text-muted">{{ $ms['transaction_count'] ?? 0 }} transaksi</div>
                    </div>
                    <div class="text-end">
                        <div class="small text-success">+Rp {{ number_format($ms['total_income'] ?? 0, 0, ',', '.') }}</div>
                        <div class="small text-danger">-Rp {{ number_format($ms['total_expense'] ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const data = @json($monthlySummaries);
    new Chart(document.getElementById('yearlyChart'), {
        type: 'bar',
        data: {
            labels: data.map(d => d.month_name ?? ''),
            datasets: [
                { label: 'Income', data: data.map(d => d.total_income ?? 0), backgroundColor: '#22C55E', borderRadius: 6 },
                { label: 'Expense', data: data.map(d => d.total_expense ?? 0), backgroundColor: '#ef4444', borderRadius: 6 }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: { y: { ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'M' } } }
        }
    });
});
</script>
@endpush
