@extends('layouts.app')

@php
$months = ['januari', 'febuari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
@endphp

@section('content')
<div class="col-12">
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
                <form action="{{ route('/action/export-realisasi') }}" method="post">
                    <input type="hidden" name="type" value="scaling">
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
                                <td>{{ ($sustain['nik']) }}</td>
                                <td>{{ ($sustain['name']) }}</td>
                                <td>{{ ($sustain['segmen']) }}</td>
                                <td>{{ ($sustain['tr']) }}</td>
                                <td>{{ ($sustain['segmen']) }}</td>
                                <td>{{ rupiah($sustain['value_januari']) }}</td>
                                <td>{{ rupiah($sustain['value_febuari']) }}</td>
                                <td>{{ rupiah($sustain['value_maret']) }}</td>
                                <td>{{ rupiah($sustain['value_april']) }}</td>
                                <td>{{ rupiah($sustain['value_mei']) }}</td>
                                <td>{{ rupiah($sustain['value_juni']) }}</td>
                                <td>{{ rupiah($sustain['value_juli']) }}</td>
                                <td>{{ rupiah($sustain['value_agustus']) }}</td>
                                <td>{{ rupiah($sustain['value_september']) }}</td>
                                <td>{{ rupiah($sustain['value_oktober']) }}</td>
                                <td>{{ rupiah($sustain['value_november']) }}</td>
                                <td>{{ rupiah($sustain['value_desember']) }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" onclick="updateScaling('{{ $sustain['user_id'] }}', '{{ $sustain['sales_id'] }}')" data-bs-toggle="modal" data-bs-target="#table-list">Detail</button>
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
                <form action="{{ route('/action/export-realisasi') }}" method="post">
                    <input type="hidden" name="type" value="scaling">
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
                                <td>{{ ($sustain['nik']) }}</td>
                                <td>{{ ($sustain['name']) }}</td>
                                <td>{{ ($sustain['segmen']) }}</td>
                                <td>{{ ($sustain['tr']) }}</td>
                                <td>{{ ($sustain['segmen']) }}</td>
                                <td>{{ rupiah($sustain['value_januari']) }}</td>
                                <td>{{ rupiah($sustain['value_febuari']) }}</td>
                                <td>{{ rupiah($sustain['value_maret']) }}</td>
                                <td>{{ rupiah($sustain['value_april']) }}</td>
                                <td>{{ rupiah($sustain['value_mei']) }}</td>
                                <td>{{ rupiah($sustain['value_juni']) }}</td>
                                <td>{{ rupiah($sustain['value_juli']) }}</td>
                                <td>{{ rupiah($sustain['value_agustus']) }}</td>
                                <td>{{ rupiah($sustain['value_september']) }}</td>
                                <td>{{ rupiah($sustain['value_oktober']) }}</td>
                                <td>{{ rupiah($sustain['value_november']) }}</td>
                                <td>{{ rupiah($sustain['value_desember']) }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" onclick="updateScaling('{{ $sustain['user_id'] }}', '{{ $sustain['sales_id'] }}')" data-bs-toggle="modal" data-bs-target="#table-list">Detail</button>
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

@include('components.modal.realisasi.list')
@endsection
