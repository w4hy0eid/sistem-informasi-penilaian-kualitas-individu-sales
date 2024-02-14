<!-- Modal -->
<div class="modal fade" id="modalFormImport" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal">Upload File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('/action/import-sales') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="container">
                        <div class="row">
                            <input type="hidden" name="id" value="">
                            <div id="alertMessage"></div>
                            <input name="file" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required="required">
                            <div class="my-3">
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
