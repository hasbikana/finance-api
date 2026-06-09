<div class="modal fade" id="editGoalModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow">
    <form id="editGoalForm" method="POST">
        @csrf @method('PUT')
        <div class="modal-header border-0"><h5 class="modal-title fw-bold">Edit Target</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Nama Target</label><input type="text" name="name" id="editGoalName" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Jumlah Target</label><input type="number" name="target_amount" id="editGoalAmt" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Target Tanggal</label><input type="date" name="target_date" id="editGoalDate" class="form-control"></div>
            <div class="mb-0"><label class="form-label">Deskripsi</label><textarea name="description" id="editGoalDesc" class="form-control" rows="2"></textarea></div>
        </div>
        <div class="modal-footer border-0"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div></div>
