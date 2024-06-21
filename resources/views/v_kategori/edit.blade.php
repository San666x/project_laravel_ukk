@extends('layouts.adm-main')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('kategori.update', $rsetKategori->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="font-weight-bold">DESKRIPSI</label>
                            <input type="text" class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" value="{{ old('deskripsi', $rsetKategori->deskripsi) }}" placeholder="Masukkan Deskripsi Kategori">

                            @error('deskripsi')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">KATEGORI</label>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kategori" id="kategoriB" value="B" {{ $rsetKategori->kategori == 'B' ? 'checked' : '' }}>
                                <label class="form-check-label" for="kategoriB">
                                    B - Bahan
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kategori" id="kategoriA" value="A" {{ $rsetKategori->kategori == 'A' ? 'checked' : '' }}>
                                <label class="form-check-label" for="kategoriA">
                                    A - Alat
                                </label>
                            </div>
                            
                            @error('kategori')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-md btn-primary">SIMPAN</button>
                        <button type="reset" class="btn btn-md btn-warning">RESET</button>

                    </form> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection