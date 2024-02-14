<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="formSales">
                    @csrf
                    <input type="hidden" name="id" value="">
                    <div id="alertMessage"></div>
                    <div class="mb-3">
                        <label for="tr">List Users</label>
                        <select class="form-select" name="user_id" id="users" aria-label="Floating label select example">
                            <option selected disabled>Open this select user</option>
                        </select>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="judul_project" id="judul_project" placeholder="judul_project">
                        <label for="nilai_project">Judul Project</label>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="nama_pelanggan" id="nama_pelanggan" placeholder="nama_pelanggan">
                                <label for="nama_pelanggan">Nama Pelanggan</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-floating mb-3">
                                <input type="number" min="1" class="form-control" name="lama_kontrak" id="lama_kontrak" placeholder="lama_kontrak" value="1">
                                <label for="lama_kontrak">Lama Kontrak</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="mitra" id="mitra" placeholder="mitra">
                                <label for="mitra">Mitra</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="deal_dibulan" id="deal_dibulan" aria-label="Floating label select example">
                                    <option selected disabled>Open this select Bulan</option>
                                    <option value="1">Januari</option>
                                    <option value="2">Febuari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                                <label for="tr">Deal dibulan</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" min="1" class="form-control" name="nilai_project" id="nilai_project" placeholder="nilai_project" value="0">
                        <label for="nilai_project">Nilai Project</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" min="1" class="form-control" name="pembayaran_bulanan" id="pembayaran_bulanan" placeholder="pembayaran_bulanan" value="0">
                        <label for="nilai_project">Pembayaran Bulanan</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" name="type" id="type" aria-label="Floating label select example">
                            <option selected disabled>Open this select Type</option>
                            <option value="ngtma">NGTMA</option>
                            <option value="existing">EXISTING</option>
                            <option value="new">NEW</option>
                        </select>
                        <label for="tr">Type</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitFormCreate">Save changes</button>
            </div>
        </div>
    </div>
</div>
