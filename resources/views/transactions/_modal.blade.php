<div class="modal fade" id="transactionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="{{ route('web.transactions.store') }}">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small">Tipe</label>
                        <select name="type" class="form-select" required>
                            <option value="expense">Pengeluaran</option>
                            <option value="income">Pemasukan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Jumlah</label>
                        <input type="number" name="amount" class="form-control" placeholder="0" required min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Kategori</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Pilih</option>
                            @foreach($categories ?? [] as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Wallet</label>
                        <select name="wallet_id" class="form-select">
                            <option value="">Tanpa Wallet</option>
                            @foreach($wallets ?? [] as $w)
                            <option value="{{ $w->id }}">{{ $w->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Tanggal</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Opsional"></textarea>
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
