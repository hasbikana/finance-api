<div class="modal fade" id="editTransactionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="editTransactionForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Edit Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small">Tipe</label>
                        <select name="type" id="editType" class="form-select" required>
                            <option value="income">Pemasukan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Jumlah</label>
                        <input type="number" name="amount" id="editAmount" class="form-control" required min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Kategori</label>
                        <select name="category_id" id="editCategory" class="form-select" required>
                            @foreach($categories ?? [] as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Wallet</label>
                        <select name="wallet_id" id="editWallet" class="form-select">
                            <option value="">Tanpa Wallet</option>
                            @foreach($wallets ?? [] as $w)
                            <option value="{{ $w->id }}">{{ $w->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Tanggal</label>
                        <input type="date" name="date" id="editDate" class="form-control" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small">Deskripsi</label>
                        <textarea name="description" id="editDescription" class="form-control" rows="2"></textarea>
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

@push('scripts')
<script>
function editTransaction(t) {
    document.getElementById('editTransactionForm').action = '/transactions/' + t.id;
    document.getElementById('editType').value = t.type;
    document.getElementById('editAmount').value = t.amount;
    document.getElementById('editCategory').value = t.category_id;
    document.getElementById('editWallet').value = t.wallet_id ?? '';
    document.getElementById('editDate').value = t.date;
    document.getElementById('editDescription').value = t.description ?? '';
    new bootstrap.Modal(document.getElementById('editTransactionModal')).show();
}
</script>
@endpush
