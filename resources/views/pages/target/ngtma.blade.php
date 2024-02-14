@extends('layouts.app')

@section('content')

@php
$months = ['januari', 'febuari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
@endphp

<div class="col-12">
    @if ($message = Session::get('error'))
    <div class="alert alert-danger">
        <ul>
            <li>{{ $message }}</li>
        </ul>
    </div>
    @endif
    <div class="bg-light rounded h-100 p-4">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link" id="dbs-tab" data-toggle="tab" href="#dbs" role="tab" aria-controls="dbs" aria-selected="true">DBS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="treg-tab" data-toggle="tab" href="#treg" role="tab" aria-controls="treg" aria-selected="false">TREG</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade" id="dbs" role="tabpanel" aria-labelledby="dbs-tab">
                <form action="{{ route('/action/export-target') }}" method="post">
                    <input type="hidden" name="type" value="ngtma">
                    <input type="hidden" name="tr" value="dbs">
                    @csrf
                    <button type="submit" class="btn btn-primary mt-3">Export</button>
                </form>
                <div class="table-responsive mt-5">
                    <table class="table" id="table-dbs">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">NIK</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Witel</th>
                                <th scope="col">Regional</th>
                                <th scope="col">Level AM</th>
                                @foreach($months as $month)
                                <th scope="col">{{ ucfirst($month) }}</th>
                                @endforeach
                                <th scope="col">Target Year</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $no = 1;
                            @endphp
                            @foreach ($data['data']['dbs'] as $sustain)
                            <tr>
                                <th scope="row">{{ $no }}</th>
                                <td>{{ ($sustain->users->nik) }}</td>
                                <td>{{ ($sustain->users->name) }}</td>
                                <td>{{ ($sustain->users->segmen) }}</td>
                                <td>{{ ($sustain->users->tr) }}</td>
                                <td>{{ ($sustain->users->segmen) }}</td>
                                <td>{{ rupiah($sustain->value_januari) }}</td>
                                <td>{{ rupiah($sustain->value_febuari) }}</td>
                                <td>{{ rupiah($sustain->value_maret) }}</td>
                                <td>{{ rupiah($sustain->april) }}</td>
                                <td>{{ rupiah($sustain->value_mei) }}</td>
                                <td>{{ rupiah($sustain->value_juni) }}</td>
                                <td>{{ rupiah($sustain->value_juli) }}</td>
                                <td>{{ rupiah($sustain->value_agustus) }}</td>
                                <td>{{ rupiah($sustain->value_september) }}</td>
                                <td>{{ rupiah($sustain->value_oktober) }}</td>
                                <td>{{ rupiah($sustain->value_november) }}</td>
                                <td>{{ rupiah($sustain->value_desember) }}</td>
                                <td>{{ rupiah($sustain->value_year) }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-sm-square btn-danger" onclick="deleteSustain('{{ $sustain->id }}')"><i class="fa fa-trash"></i></button>
                                    <button type="button" class="btn btn-sm btn-sm-square btn-info" onclick="updateUserBtn('{{ $sustain->id }}')" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-pencil-alt"></i></button>
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
            <div class="tab-pane fade" id="treg" role="tabpanel" aria-labelledby="dbs-tab">
                <form action="{{ route('/action/export-target') }}" method="post">
                    <input type="hidden" name="type" value="ngtma">
                    <input type="hidden" name="tr" value="tr">
                    @csrf
                    <button type="submit" class="btn btn-primary mt-3">Export</button>
                </form>
                <div class="table-responsive mt-5">
                    <table class="table" id="table-treg">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">NIK</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Witel</th>
                                <th scope="col">Regional</th>
                                <th scope="col">Level AM</th>
                                @foreach($months as $month)
                                <th scope="col">{{ ucfirst($month) }}</th>
                                @endforeach
                                <th scope="col">Target Year</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $no = 1;
                            @endphp
                            @foreach ($data['data']['treg'] as $sustain)
                            <tr>
                                <th scope="row">{{ $no }}</th>
                                <td>{{ ($sustain->users->nik) }}</td>
                                <td>{{ ($sustain->users->name) }}</td>
                                <td>{{ ($sustain->users->segmen) }}</td>
                                <td>{{ ($sustain->users->tr) }}</td>
                                <td>{{ ($sustain->users->segmen) }}</td>
                                <td>{{ rupiah($sustain->value_januari) }}</td>
                                <td>{{ rupiah($sustain->value_febuari) }}</td>
                                <td>{{ rupiah($sustain->value_maret) }}</td>
                                <td>{{ rupiah($sustain->april) }}</td>
                                <td>{{ rupiah($sustain->value_mei) }}</td>
                                <td>{{ rupiah($sustain->value_juni) }}</td>
                                <td>{{ rupiah($sustain->value_juli) }}</td>
                                <td>{{ rupiah($sustain->value_agustus) }}</td>
                                <td>{{ rupiah($sustain->value_september) }}</td>
                                <td>{{ rupiah($sustain->value_oktober) }}</td>
                                <td>{{ rupiah($sustain->value_november) }}</td>
                                <td>{{ rupiah($sustain->value_desember) }}</td>
                                <td>{{ rupiah($sustain->value_year) }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-sm-square btn-danger" onclick="deleteSustain('{{ $sustain->id }}')"><i class="fa fa-trash"></i></button>
                                    <button type="button" class="btn btn-sm btn-sm-square btn-info" onclick="updateUserBtn('{{ $sustain->id }}')" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-pencil-alt"></i></button>
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
    </div>
</div>

<!-- add button bottom right -->
<div class="circle_bottom" onclick="clickCircleBottom()">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-columns" viewBox="0 0 16 16">
        <path d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V2zm8.5 0v8H15V2H8.5zm0 9v3H15v-3H8.5zm-1-9H1v3h6.5V2zM1 14h6.5V6H1v8z" />
    </svg>
</div>

<div class="content_bottom_right" style="display: none;">
    <!-- <i class="bi-cloud-upload"></i> -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-4 wrap_content_bottom" data-bs-toggle="modal" data-bs-target="#modalFormImportTarget" onclick="newTargetUpload()">
                <i class="bi bi-cloud-upload"></i>
                <span class="content_bottom_text">Upload</span>
            </div>
            <div class="col-4 wrap_content_bottom" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="newTarget()">
                <i class="bi bi-plus-circle"></i>
                <span class="content_bottom_text">Create</span>
            </div>
            <div class="col-4 wrap_content_bottom">
                <i class="bi bi-collection"></i>
                <span class="content_bottom_text" onclick="sampleTarget()">Sample</span>
            </div>
        </div>
    </div>
</div>

@include('components.modal.target.create')
@include('components.modal.import.target')
@endsection
