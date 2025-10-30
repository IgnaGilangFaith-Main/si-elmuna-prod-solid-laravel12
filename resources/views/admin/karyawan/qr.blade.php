@extends('layout.admin')
@section('title', 'Data Karyawan')
@section('content')
    <h1 class="text-center">
        DATA KARYAWAN
    </h1>
    <div class="col-12 col-md-12 my-3 text-center">
        {!! $qrCode !!}
    </div>
    <div class="col-12 col-md-12 text-center mb-3">
        <h2>
            {{ $data->nama }}
        </h2>
        <h3>
            {{ $data->jabatan }}
        </h3>
        <center>
            <img src="{{ asset('foto_karyawan' . '/' . $data->foto_karyawan) }}" alt="Foto Karyawan {{ $data->nama }}"
                class="img-thumbnail" width="20%">
        </center>
    </div>
    <div class="col-12 col-md-12 text-center">
        <a href="{{ url('/karyawan') }}" class="btn btn-secondary">Kembali</a>
    </div>
@endsection
