<div class="modal fade" id="goalModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow">
    <form method="POST" action="{{ route('web.goals.store') }}">
        @csrf
        <div class="modal-header border-0"><h5 class="modal-title fw-bold">Tambah Target Tabungan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Nama Target</label><input type="text" name="name" class="form-control" placeholder="Contoh: Beli Motor" required></div>
            <div class="mb-3"><label class="form-label">Jumlah Target</label><input type="number" name="target_amount" class="form-control" required min="1"></div>
            <div class="mb-3"><label class="form-label">Target Tanggal</label><input type="date" name="target_date" class="form-control"></div>
            <div class="mb-0"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="2" placeholder="Opsional"></textarea></div>
        </div>
        <div class="modal-footer border-0"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div></div>
