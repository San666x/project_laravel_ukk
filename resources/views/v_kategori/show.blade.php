@extends('layouts.adm-main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
               <div class="card border-0 shadow rounded">
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td>Deskripsi</td>
                                <td>{{ $rsetKategori->deskripsi }}</td>
                            </tr>
                            <tr>
                                <td>Kategori</td>
                                <td>
                                    <span style="font-size: 1rem;">
                                    {{ $rsetKategori->kategori }}
                                    </span>
                                    @if ($rsetKategori->kategori == 'A')
                                        <span class="badge badge-success badge-inline">Alat</span>
                                    
                                    @elseif ($rsetKategori->kategori == 'B')
                                        <span class="badge badge-primary">Bahan</span>
                                    
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
               </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12  text-center">
                
                <a href="{{ route('kategori.index') }}" class="btn btn-md btn-primary mt-3">Back</a>
            </div>
        </div>
    </div>
@endsection