@extends('layouts.app')

@section('content')
<div class="col-12">
    @if ($message = Session::get('error'))
    <div class="alert alert-danger">
        <ul>
            <li>{{ $message }}</li>
        </ul>
    </div>
    @endif
    <div class="bg-light rounded h-100 p-4">
        <a href="{{ url('example/sample_import_user.xlsx') }}" class="btn btn-primary mb-2">Sample</a>
        <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modalFormImport">Import</button>
        <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="newUserBtn()">
            Tambah Data
        </button>
        <div class="table-responsive">
            <table class="table" id="userTable">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">UserID</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Nik</th>
                        <th scope="col">Email</th>
                        <th scope="col">Segmen</th>
                        <th scope="col">Level</th>
                        <th scope="col">TR</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $no = 1;
                    @endphp
                    @foreach ($data['data'] as $user)
                    <tr>
                        <th scope="row">{{ $no }}</th>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->nik }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->segmen }}</td>
                        <td>{{ $user->level }}</td>
                        <td>{{ $user->tr }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-sm-square btn-danger" onclick="deleteSales('{{ $user->id }}')"><i class="fa fa-trash"></i></button>
                            <button type="button" class="btn btn-sm btn-sm-square btn-info" onclick="updateUserBtn('{{ $user->id }}')" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-pencil-alt"></i></button>
                        </td>
                    </tr>
                    @php
                    $no++;
                    @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalFormImport" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal">Upload File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('/action/user/import') }}" enctype="multipart/form-data">
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


@include('components.modal.user.create')
@endsection
