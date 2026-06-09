@extends('layouts.app')
@section('title', 'Laporan Kategori - FinanceApp')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Laporan Berdasarkan Kategori</h4>
</div>

<form method="GET" class="mb-4">
    <div class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label small">Dari</label><input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}"></div>
        <div class="col-md-3"><label class="form-label small">Sampai</label><input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}"></div>
        <div class="col-md-2"><button class="btn btn-outline-secondary btn-sm">Filter</button></div>
    </div>
</form>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0"><h6 class="fw-bold mb-0">Pengeluaran per Kategori</h6></div>
            <div class="card-body"><canvas id="expenseCategoryChart" height="300"></canvas></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0"><h6 class="fw-bold mb-0">Pemasukan per Kategori</h6></div>
            <div class="card-body"><canvas id="incomeCategoryChart" height="300"></canvas></div>
        </div>
    </div>
</div>
<div class="card border-0 shadow-sm mt-3">
    <div class="card-header bg-white border-0"><h6 class="fw-bold mb-0">Trend Harian</h6></div>
    <div class="card-body"><canvas id="dailyChart" height="250"></canvas></div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const charts = @json($charts ?? []);

    new Chart(document.getElementById('expenseCategoryChart'), {
        type: 'pie',
        data: {
            labels: (charts.expense_by_category || []).map(d => d.category_name),
            datasets: [{ data: (charts.expense_by_category || []).map(d => d.total), backgroundColor: ['#ef4444','#f59e0b','#3b82f6','#8b5cf6','#ec4899','#06b6d4','#84cc16','#f97316'] }]
        }
    });

    new Chart(document.getElementById('incomeCategoryChart'), {
        type: 'pie',
        data: {
            labels: (charts.income_by_category || []).map(d => d.category_name),
            datasets: [{ data: (charts.income_by_category || []).map(d => d.total), backgroundColor: ['#22C55E','#0F766E','#14B8A6','#10b981','#34d399','#6ee7b7','#a7f3d0','#d1fae5'] }]
        }
    });

    const daily = charts.daily_trend || [];
    new Chart(document.getElementById('dailyChart'), {
        type: 'line',
        data: {
            labels: daily.map(d => d.date),
            datasets: [
                { label: 'Income', data: daily.map(d => d.income), borderColor: '#22C55E', backgroundColor: 'rgba(34,197,94,0.1)', fill: true, tension: 0.3 },
                { label: 'Expense', data: daily.map(d => d.expense), borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,0.1)', fill: true, tension: 0.3 }
            ]
        },
        options: { responsive: true }
    });
});
</script>
@endpush
