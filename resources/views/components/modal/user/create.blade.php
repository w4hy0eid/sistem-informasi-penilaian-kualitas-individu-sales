<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('/action/user/create') }}" method="post" id="formCreateUser">
                    @csrf
                    <input type="hidden" name="id" value="">
                    <div id="alertMessage"></div>
                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control" id="fullName" placeholder="Agus Firmawan">
                        <label for="fullName">Full Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="nik" class="form-control" id="nik" placeholder="NLMSNKWL">
                        <label for="nik">NIK</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com">
                        <label for="email">Email</label>
                    </div>
                    <div class="form-floating mb-3" id="input-password">
                        <input type="password" name="password" class="form-control" id="password" placeholder="password">
                        <label for="password">Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" name="tr" id="tr" aria-label="Floating label select example">
                            <option selected disabled>Open this select TR/DBS</option>
                            <option value="TR1">TR1</option>
                            <option value="TR2">TR2</option>
                            <option value="TR3">TR3</option>
                            <option value="TR4">TR4</option>
                            <option value="TR5">TR5</option>
                            <option value="TR6">TR6</option>
                            <option value="TR7">TR7</option>
                            <option value="DBS">DBS</option>
                        </select>
                        <label for="tr">TR</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" name="level" id="level" aria-label="Floating label select example">
                            <option selected>Open this select menu</option>
                            <option value="SAM">SAM</option>
                            <option value="AM">AM</option>
                            <option value="AM1">AM1</option>
                            <option value="AM2">AM2</option>
                            <option value="AM3">AM3</option>
                            <option value="AM3M">AM3 Multidivisi</option>
                        </select>
                        <label for="level">Levels</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="segmen" id="segmen" placeholder="Jakart barat, Bandung ,...">
                        <label for="segmen">Segmen</label>
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
