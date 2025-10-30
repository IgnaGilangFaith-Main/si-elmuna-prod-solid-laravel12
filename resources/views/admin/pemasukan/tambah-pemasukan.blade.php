@extends('layout.admin')
@section('title', 'Tambah Pemasukan')
@section('content')
    <div class="card">
        <div class="card-header">
            <center>
                <h3>Form Tambah Data Pemasukan</h3>
            </center>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger mx-2">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ url('/tambah-pemasukan') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="ket_pemasukan" class="form-label">Keterangan Pemasukan</label>
                    <input type="text" name="ket_pemasukan" id="ket_pemasukan" class="form-control"
                        value="{{ old('ket_pemasukan') }}">
                </div>
                <div class="mb-3">
                    <label for="jumlah_pemasukan" class="form-label">Jumlah Pemasukan</label>
                    <input type="number" name="jumlah_pemasukan" id="jumlah_pemasukan" class="form-control"
                        value="{{ old('jumlah_pemasukan') }}">
                </div>
                <div class="mb-3">
                    <label for="created_at" class="form-label">Tanggal Pemasukan</label>
                    <input type="date" name="created_at" id="created_at" class="form-control"
                        value="{{ old('created_at') }}">
                </div>
                <div class="my-2">
                    <center>
                        <a href="{{ url('/pemasukan') }}" class="btn btn-secondary">Kembali</a>
                        <button type="reset" class="btn btn-danger mx-2">Batal</button>
                        <button type="submit" class="btn btn-success">Kirim</button>
                    </center>
                </div>
            </form>
        </div>
    </div>
@endsection
