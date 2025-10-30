@extends('layout.admin')
@section('title', 'Edit Data Pengeluaran')
@section('content')
    <div class="card">
        <div class="card-header">
            <center>
                <h3>Form Edit Data Pengeluaran</h3>
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
            <form action="{{ url('/edit-pengeluaran/' . $data->id) }}" method="post">
                @csrf
                @method('put')
                <div class="mb-3">
                    <label for="ket_pengeluaran" class="form-label">Keterangan Pengeluaran</label>
                    <input type="text" name="ket_pengeluaran" id="ket_pengeluaran" class="form-control"
                        value="{{ old('ket_pengeluaran', $data->ket_pengeluaran) }}">
                </div>
                <div class="mb-3">
                    <label for="jumlah_pengeluaran" class="form-label">Jumlah Pengeluaran</label>
                    <input type="number" name="jumlah_pengeluaran" id="jumlah_pengeluaran" class="form-control"
                        value="{{ old('jumlah_pengeluaran', $data->jumlah_pengeluaran) }}">
                </div>
                <div class="mb-3">
                    <label for="created_at" class="form-label">Tanggal Pengeluaran</label>
                    <input type="date" name="created_at" id="created_at" class="form-control"
                        value="{{ old('created_at', $data->created_at->format('Y-m-d')) }}">
                </div>
                <div class="my-2">
                    <center>
                        <a href="{{ url('/pengeluaran') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-success ms-2">Kirim</button>
                    </center>
                </div>
            </form>
        </div>
    </div>
@endsection
