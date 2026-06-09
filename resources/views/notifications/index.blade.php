@extends('layouts.app')
@section('title', 'Notifikasi - FinanceApp')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Notifikasi</h4>
    @if($notifications->isNotEmpty())
    <form action="{{ route('web.notifications.readAll') }}" method="POST">
        @csrf
        <button class="btn btn-sm btn-outline-secondary">Tandai Semua Dibaca</button>
    </form>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="list-group list-group-flush">
        @forelse($notifications as $n)
        <div class="list-group-item {{ $n->is_read ? '' : 'bg-light bg-opacity-50' }}">
            <div class="d-flex justify-content-between align-items-start">
                <div class="d-flex gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px;height:40px;background:{{ $n->type === 'budget_overspent' ? '#fef2f2' : ($n->type === 'goal_reached' ? '#f0fdf4' : '#eff6ff') }};">
                        <i class="bi {{ $n->type === 'budget_overspent' ? 'bi-exclamation-triangle text-danger' : ($n->type === 'goal_reached' ? 'bi-trophy text-success' : 'bi-bell text-primary') }}"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold {{ $n->is_read ? '' : 'text-dark' }}">{{ $n->title }}</span>
                            @if(!$n->is_read)
                            <span class="badge bg-primary rounded-pill" style="width:8px;height:8px;"></span>
                            @endif
                        </div>
                        <p class="mb-1 small">{{ $n->message }}</p>
                        <small class="text-muted">{{ $n->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                @if(!$n->is_read)
                <form action="{{ route('web.notifications.read', $n->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-link text-decoration-none">Tandai Dibaca</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-bell-slash display-4 d-block mb-2"></i>
            Belum ada notifikasi
        </div>
        @endforelse
    </div>
    @if($notifications->hasPages())
    <div class="card-footer bg-white">{{ $notifications->links() }}</div>
    @endif
</div>
@endsection
