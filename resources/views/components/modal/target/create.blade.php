@php
    $months = ['januari', 'febuari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
@endphp
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="formTarget">
                    @csrf
                    <input type="hidden" name="id" value="">
                    <div id="alertMessage"></div>
                    <div class="mb-3">
                        <label for="tr">List Users</label>
                        <select class="form-select" name="user_id" id="users" aria-label="Floating label select example">
                            <option selected disabled>Open this select user</option>
                        </select>
                    </div>
                    <div class="row">
                        @foreach($months as $month)
                        <div class="col-sm-6">
                            <div class="form-floating mb-3">
                                <input type="number" min="0" class="form-control" name="value_{{$month}}" id="value_{{$month}}" placeholder="Value Month {{ $month }}" value="0">
                                <label for="value">Target {{ $month }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" min="0" class="form-control" name="value_year" id="value_year" placeholder="Value Year" readonly>
                        <label for="value">Target Year</label>
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
