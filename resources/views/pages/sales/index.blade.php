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
    <a href="{{ url('example/sample_sales.xlsx') }}" class="btn btn-primary">Sample</a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalFormImport">Import</button>
        <div class="table-responsive mt-5">
            <table class="table" id="table-sales">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Level</th>
                        <th scope="col">Judul Project</th>
                        <th scope="col">Nama Pelanggan</th>
                        <th scope="col">Mitra</th>
                        <th scope="col">Deal Bulan</th>
                        <th scope="col">Nilai Project</th>
                        <th scope="col">Pembayaran Bulanan</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $no = 1;
                    @endphp
                    @foreach ($data['data'] as $sustain)
                    <tr>
                        <th scope="row">{{ $no }}</th>
                        <td>{{ ($sustain->users->name) }}</td>
                        <td>{{ ($sustain->users->level) }}</td>
                        <td>{{ ($sustain->judul_project) }}</td>
                        <td>{{ ($sustain->nama_pelanggan) }}</td>
                        <td>{{ ($sustain->mitra) }}</td>
                        <td>{{ ($sustain->deal_dibulan) }}</td>
                        <td>{{ rupiah($sustain->nilai_project) }}</td>
                        <td>{{ rupiah($sustain->pembayaran_bulanan) }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-sm-square btn-danger" onclick="deleteSalesBtn('{{ $sustain->id }}')"><i class="fa fa-trash"></i></button>
                            <button type="button" class="btn btn-sm btn-sm-square btn-info" onclick="updateSalesBtn('{{ $sustain->id }}')" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-pencil-alt"></i></button>
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

<!-- add button bottom right -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square btn-right-bottom" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="newSales()"><i class="bi bi-patch-plus"></i></a>

@include('components.modal.sales.index')
@include('components.modal.import.index')
@endsection
