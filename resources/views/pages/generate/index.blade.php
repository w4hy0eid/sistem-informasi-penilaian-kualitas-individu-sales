@extends('layouts.app')

@php
$year = date('Y');
@endphp

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light rounded h-100 p-4">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    <ul>
                        <li>{{ $message }}</li>
                    </ul>
                </div>
                @endif
                <h6 class="mb-4">Generate Report</h6>
                <form action="{{ route('/action/generate-report') }}" method="post">
                    @csrf
                    <div class="form-floating mb-3">
                        <select class="form-select" name="month" id="month" aria-label="Floating label select example">
                            <option selected disabled>Pilih Bulan</option>
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
                        <select class="form-select" name="year" id="year" aria-label="Floating label select example">
                            <option selected disabled>Pilih Tahun</option>
                            @for($i = $year; $i < ($year + 5); $i++) <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                        </select>
                        <label for="tr">Tahun</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Generate</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
