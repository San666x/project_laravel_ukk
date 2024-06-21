@extends('layouts.adm-main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow rounded">
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td>Tanggal Masuk</td>
                                <td>{{ $rsetBarangmasuk->tgl_masuk }}</td>
                            </tr>
                            <tr>
                                <td>Quantity Masuk</td>
                                <td>{{ $rsetBarangmasuk->qty_masuk }}</td>
                            </tr>
                            <!-- <tr>
                                <td>Spesifikasi</td>
                                <td>{{ $rsetBarangmasuk->spesifikasi }}</td>
                            </tr>
                            <tr>
                                <td>Stok</td>
                                <td>{{ $rsetBarangmasuk->stok }}</td>
                            </tr> -->
                            <tr>
                                <td>Barang_id</td>
                                <td>{{ $rsetBarangmasuk->barang_id }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <a href="{{ route('barangmasuk.index') }}" class="btn btn-md btn-primary mb-3">Back</a>
            </div>
        </div>
    </div>
@endsection