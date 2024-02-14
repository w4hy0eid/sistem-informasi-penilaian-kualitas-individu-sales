<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="formRealisasi">
                    @csrf
                    <input type="hidden" name="id" value="">
                    <div id="alertMessage"></div>
                    <div class="form-floating mb-3">
                        <select class="form-select" name="sales_id" id="sales_id" aria-label="Floating label select example">
                            <option selected disabled>Open this select Sales</option>
                            @for($i = 0; $i < count($sales['data']); $i++)
                             <option value="{{$sales['data'][$i]->id }}">{{ $sales['data'][$i]->judul_project }} - {{ $sales['data'][$i]->nama_pelanggan }}</option>
                            @endfor
                        </select>
                        <label for="tr">Sales</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" name="month" id="month" aria-label="Floating label select example">
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
                        <label for="tr">Bulan</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" min="0" class="form-control" name="value" id="value" placeholder="Value">
                        <label for="value">Value</label>
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
