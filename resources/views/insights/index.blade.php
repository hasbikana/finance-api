@extends('layouts.app')
@section('title', 'Financial Insight - FinanceApp')
@section('content')
<h4 class="fw-bold mb-4">Financial Insight</h4>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-stat p-3">
            <small class="text-muted">Pemasukan Bulan Ini</small>
            <h5 class="fw-bold text-success mb-0">Rp {{ number_format($comparison['current_month']['income'] ?? 0, 0, ',', '.') }}</h5>
            <small class="{{ ($comparison['income_trend'] ?? '') === 'up' ? 'text-success' : 'text-danger' }}">
                {{ ($comparison['income_trend'] ?? '') === 'up' ? 'Naik' : 'Turun' }} {{ abs($comparison['income_change_percent'] ?? 0) }}% dari bulan lalu
            </small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat p-3">
            <small class="text-muted">Pengeluaran Bulan Ini</small>
            <h5 class="fw-bold text-danger mb-0">Rp {{ number_format($comparison['current_month']['expense'] ?? 0, 0, ',', '.') }}</h5>
            <small class="{{ ($comparison['expense_trend'] ?? '') === 'down' ? 'text-success' : 'text-danger' }}">
                {{ ($comparison['expense_trend'] ?? '') === 'up' ? 'Naik' : 'Turun' }} {{ abs($comparison['expense_change_percent'] ?? 0) }}% dari bulan lalu
            </small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat p-3">
            <small class="text-muted">Savings Rate</small>
            <h5 class="fw-bold mb-0" style="color: #0F766E;">{{ $comparison['current_month']['savings_rate'] ?? 0 }}%</h5>
            <small>Dari total pemasukan bulan ini</small>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0"><h6 class="fw-bold mb-0">Perbandingan Bulan Ini vs Bulan Lalu</h6></div>
    <div class="card-body">
        <canvas id="comparisonChart" height="250"></canvas>
    </div>
</div>

<h5 class="fw-bold mb-3">Insight Otomatis</h5>
<div class="row g-3">
    @foreach($insights as $insight)
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-start gap-3">
                <div class="icon rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;
                    {{ $insight['type'] === 'warning' ? 'background:#fef2f2;color:#ef4444' : ($insight['type'] === 'info' ? 'background:#eff6ff;color:#3b82f6' : 'background:#f0fdf4;color:#22C55E') }}">
                    <i class="bi {{ $insight['icon'] }} fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">{{ $insight['message'] }}</h6>
                    <p class="text-muted small mb-0">{{ $insight['detail'] }}</p>
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
    const comp = @json($comparison);
    new Chart(document.getElementById('comparisonChart'), {
        type: 'bar',
        data: {
            labels: ['Income', 'Expense', 'Balance'],
            datasets: [
                { label: 'Bulan Ini', data: [comp.current_month?.income||0, comp.current_month?.expense||0, comp.current_month?.balance||0], backgroundColor: '#0F766E', borderRadius: 6 },
                { label: 'Bulan Lalu', data: [comp.last_month?.income||0, comp.last_month?.expense||0, comp.last_month?.balance||0], backgroundColor: '#14B8A6', borderRadius: 6 }
            ]
        },
        options: { responsive: true, plugins: { legend: { position: 'top' } } }
    });
});
</script>
@endpush
