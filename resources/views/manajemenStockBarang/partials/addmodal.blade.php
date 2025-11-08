<!-- Tambah Barang Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addForm" class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Barang Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Supplier</label>
                    <select class="form-select" id="addSupplierId" required>
                        <option value="">Pilih Supplier</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" id="addName" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <input type="text" class="form-control" id="addCategory">
                </div>
                <div class="mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" class="form-control" id="addStock" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Unit</label>
                    <input type="text" class="form-control" id="addUnit">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>