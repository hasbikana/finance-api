@extends('layouts.app')
@section('title', 'Wallet - FinanceApp')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Wallet</h4>
        <p class="text-muted mb-0">Total: <strong>Rp {{ number_format($totalBalance, 0, ',', '.') }}</strong></p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#walletModal"><i class="bi bi-plus-lg me-1"></i>Tambah</button>
</div>
<div class="row g-3">
    @forelse($wallets as $w)
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge {{ $w->type === 'cash' ? 'bg-success' : ($w->type === 'bank' ? 'bg-primary' : 'bg-info') }} small">
                            {{ $w->type === 'cash' ? 'Cash' : ($w->type === 'bank' ? 'Bank' : 'E-Wallet') }}
                        </span>
                        <h6 class="fw-bold mt-2 mb-0">{{ $w->name }}</h6>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item" onclick="editWallet({{ $w }})"><i class="bi bi-pencil me-2"></i>Edit</button></li>
                            <li>
                                <form action="{{ route('web.wallets.destroy', $w->id) }}" method="POST" class="delete-form d-inline">
                                    @csrf @method('DELETE')
                                    <button class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Hapus</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <h4 class="fw-bold {{ $w->balance >= 0 ? 'text-success' : 'text-danger' }}">
                    Rp {{ number_format($w->balance, 0, ',', '.') }}
                </h4>
                <small class="text-muted">{{ $w->is_active ? 'Aktif' : 'Nonaktif' }}</small>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">Belum ada wallet</div>
    @endforelse
</div>

<div class="modal fade" id="walletModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow">
        <form method="POST" action="{{ route('web.wallets.store') }}">
            @csrf
            <div class="modal-header border-0"><h5 class="modal-title fw-bold">Tambah Wallet</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Nama</label><input type="text" name="name" class="form-control" placeholder="Contoh: BCA, GoPay" required></div>
                <div class="mb-3"><label class="form-label">Tipe</label><select name="type" class="form-select" required><option value="cash">Cash</option><option value="bank">Bank</option><option value="ewallet">E-Wallet</option></select></div>
                <div class="mb-0"><label class="form-label">Saldo Awal</label><input type="number" name="balance" class="form-control" value="0" min="0"></div>
            </div>
            <div class="modal-footer border-0"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
    </div></div>
</div>

<div class="modal fade" id="editWalletModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow">
        <form id="editWalletForm" method="POST">
            @csrf @method('PUT')
            <div class="modal-header border-0"><h5 class="modal-title fw-bold">Edit Wallet</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Nama</label><input type="text" name="name" id="editWalletName" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Tipe</label><select name="type" id="editWalletType" class="form-select" required><option value="cash">Cash</option><option value="bank">Bank</option><option value="ewallet">E-Wallet</option></select></div>
                <div class="mb-0"><label class="form-label">Saldo</label><input type="number" name="balance" id="editWalletBalance" class="form-control" min="0"></div>
            </div>
            <div class="modal-footer border-0"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection

@push('scripts')
<script>
function editWallet(w) {
    document.getElementById('editWalletForm').action = '/wallets/' + w.id;
    document.getElementById('editWalletName').value = w.name;
    document.getElementById('editWalletType').value = w.type;
    document.getElementById('editWalletBalance').value = w.balance;
    new bootstrap.Modal(document.getElementById('editWalletModal')).show();
}
</script>
@endpush
