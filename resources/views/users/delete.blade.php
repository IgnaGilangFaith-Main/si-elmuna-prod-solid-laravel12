@extends('layout.admin')
@section('title', 'Elmuna - Hapus Data User')
@section('content')
    <h1 class="text-center">Hapus Data User</h1>
    <div class="col-md-12 my-3 text-center">
        <h3>Apakah anda yakin ingin menghapus User dibawah ini?</h3>
        <div class="alert alert-danger" role="alert">
            <h3><i class="bx bx-error"></i>Data yang dihapus tidak dapat dikembalikan!</h3>
        </div>
        <h4>
            <table class="table">
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $data->name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td>{{ $data->email }}</td>
                </tr>
            </table>
            <div class="mt-3">
                <form action="{{ url('/user/' . $data->id . '/delete') }}" method="post">
                    @csrf
                    @method('delete')
                    <a href="{{ url('/user') }}" class="btn btn-secondary mx-3">Kembali</a>
                    <button class="btn btn-danger mx-3" type="submit">Hapus</button>
                </form>
            </div>
        </h4>
    </div>
@endsection
