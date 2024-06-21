@extends('layouts.adm-main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow rounded">
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td>Tanggal Keluar</td>
                                <td>{{ $rsetBarangkeluar->tgl_keluar }}</td>
                            </tr>
                            <tr>
                                <td>Quantity keluar</td>
                                <td>{{ $rsetBarangkeluar->qty_keluar }}</td>
                            </tr>
                            <!-- <tr>
                                <td>Spesifikasi</td>
                                <td>{{ $rsetBarangkeluar->spesifikasi }}</td>
                            </tr>
                            <tr>
                                <td>Stok</td>
                                <td>{{ $rsetBarangkeluar->stok }}</td>
                            </tr> -->
                            <tr>
                                <td>Barang_id</td>
                                <td>{{ $rsetBarangkeluar->barang_id }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <a href="{{ route('barangkeluar.index') }}" class="btn btn-md btn-primary mb-3">Back</a>
            </div>
        </div>
    </div>
@endsection