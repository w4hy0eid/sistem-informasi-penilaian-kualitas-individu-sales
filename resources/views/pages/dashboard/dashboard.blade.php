@extends('layouts.app')

@section('content')
<div class="row h-50 justify-content-center align-items-center">
    <div class="col-xl-3">
        <div class="card">
            <div class="card-body">
                <p class="card-text">Total Pembayaran {{ rupiah($totalPembayaran) }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card">
            <div class="card-body">
                <p class="card-text">Total User {{ $totalUser }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
