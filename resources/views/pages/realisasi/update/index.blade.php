@extends('layouts.app')

@section('content')
<div class="col-12">
    <div class="bg-light rounded h-100 p-4">
        <form method="post" id="formRealisasi">
            @csrf
            <input type="hidden" name="id" value="{{ $data['data']->id }}">
            <div id="alertMessage"></div>
            <div class="form-floating mb-3">
                <select class="form-select" name="sales_id" id="sales_id" aria-label="Floating label select example">
                    <option selected disabled>Open this select Sales</option>
                    @for($i = 0; $i < count($sales['data']); $i++)
                        <option value="{{$sales['data'][$i]->id }}" {{ $sales['data'][$i]->id == $data['data']->sales_id ? "selected" : ""}}>{{ $sales['data'][$i]->judul_project }} - {{ $sales['data'][$i]->nama_pelanggan }}</option>
                    @endfor
                </select>
                <label for="tr">Sales</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" name="month" id="month" aria-label="Floating label select example">
                    <option selected disabled>Open this select Bulan</option>
                    <option value="1" {{ $data['data']->month == 1 ? "selected" : "" }}>Januari</option>
                    <option value="2" {{ $data['data']->month == 2 ? "selected" : "" }}>Febuari</option>
                    <option value="3" {{ $data['data']->month == 3 ? "selected" : "" }}>Maret</option>
                    <option value="4" {{ $data['data']->month == 4 ? "selected" : "" }}>April</option>
                    <option value="5" {{ $data['data']->month == 5 ? "selected" : "" }}>Mei</option>
                    <option value="6" {{ $data['data']->month == 6 ? "selected" : "" }}>Juni</option>
                    <option value="7" {{ $data['data']->month == 7 ? "selected" : "" }}>Juli</option>
                    <option value="8" {{ $data['data']->month ==8 ? "selected" : "" }}>Agustus</option>
                    <option value="9" {{ $data['data']->month ==9 ? "selected" : "" }}>September</option>
                    <option value="10" {{ $data['data']->month ==10 ? "selected" : "" }}>Oktober</option>
                    <option value="11" {{ $data['data']->month ==11 ? "selected" : "" }}>November</option>
                    <option value="12" {{ $data['data']->month ==12 ? "selected" : "" }}>Desember</option>
                </select>
                <label for="tr">Bulan</label>
            </div>
            <div class="form-floating mb-3">
                <input type="number" min="0" class="form-control" name="value" id="value" placeholder="Value" value="{{ $data['data']->value }}">
                <label for="value">Value</label>
            </div>
        </form>
        <button type="button" class="btn btn-primary" id="submitFormCreate">Save changes</button>
    </div>
</div>
@endsection
