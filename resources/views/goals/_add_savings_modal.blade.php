<div class="modal fade" id="addSavingsModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content border-0 shadow">
    <form id="addSavingsForm" method="POST">
        @csrf
        <div class="modal-header border-0"><h5 class="modal-title fw-bold">Tambah Tabungan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <label class="form-label">Jumlah</label>
            <input type="number" name="amount" class="form-control" required min="1" placeholder="Masukkan jumlah">
        </div>
        <div class="modal-footer border-0"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div></div>

@push('scripts')
<script>
function editGoal(g) {
    document.getElementById('editGoalForm').action = '/goals/' + g.id;
    document.getElementById('editGoalName').value = g.name;
    document.getElementById('editGoalAmt').value = g.target_amount;
    document.getElementById('editGoalDate').value = g.target_date ?? '';
    document.getElementById('editGoalDesc').value = g.description ?? '';
    new bootstrap.Modal(document.getElementById('editGoalModal')).show();
}
function addSavings(id) {
    document.getElementById('addSavingsForm').action = '/goals/' + id + '/add-savings';
    new bootstrap.Modal(document.getElementById('addSavingsModal')).show();
}
</script>
@endpush
