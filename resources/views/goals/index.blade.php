@extends('layouts.app')
@section('title', 'Target Tabungan - FinanceApp')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Savings Goal</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#goalModal"><i class="bi bi-plus-lg me-1"></i>Tambah</button>
</div>

<div class="row g-3">
    @forelse($goals as $goal)
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div style="width:80px;height:80px;margin:0 auto;">
                        <svg viewBox="0 0 36 36" class="circular-chart">
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                fill="none" stroke="#e9ecef" stroke-width="3"/>
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                fill="none" stroke="{{ $goal->status === 'completed' ? '#22C55E' : '#0F766E' }}"
                                stroke-width="3" stroke-dasharray="{{ $goal->progress_percentage }}, 100"/>
                            <text x="18" y="20.5" text-anchor="middle" font-size="7" fill="#333">{{ $goal->progress_percentage }}%</text>
                        </svg>
                    </div>
                </div>
                <h6 class="fw-bold">{{ $goal->name }}</h6>
                <p class="small text-muted mb-2">{{ $goal->description }}</p>
                <div class="d-flex justify-content-between small mb-2">
                    <span>Terkumpul: <strong>Rp {{ number_format($goal->current_amount, 0, ',', '.') }}</strong></span>
                    <span>Target: <strong>Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</strong></span>
                </div>
                @if($goal->target_date)
                    <small class="text-muted">Target: {{ $goal->target_date->format('d M Y') }}</small>
                @endif
                <span class="badge ms-2 {{ $goal->status === 'completed' ? 'bg-success' : ($goal->status === 'cancelled' ? 'bg-secondary' : 'bg-primary') }}">
                    {{ $goal->status }}
                </span>

                @if($goal->status === 'active')
                <div class="mt-3 d-flex justify-content-center gap-2">
                    <button class="btn btn-sm btn-outline-primary" onclick="addSavings({{ $goal->id }})">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Tabungan
                    </button>
                </div>
                @endif

                <div class="mt-2">
                    <div class="dropdown d-inline">
                        <button class="btn btn-sm btn-light" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item" onclick="editGoal({{ $goal }})"><i class="bi bi-pencil me-2"></i>Edit</button></li>
                            <li><form action="{{ route('web.goals.destroy', $goal->id) }}" method="POST" class="delete-form"><button class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Hapus</button></form></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">Belum ada target tabungan</div>
    @endforelse
</div>

@include('goals._modal')
@include('goals._edit_modal')
@include('goals._add_savings_modal')
@endsection
