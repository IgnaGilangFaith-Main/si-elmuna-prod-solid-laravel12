@extends('layout.admin')
@section('title', 'Elmuna - Data User')
@section('content')
    <h1 class="text-center">Data User</h1>
    <div class="my-3">
        <a href="{{ url('/user/create') }}" class="btn btn-primary">Tambah User</a>
    </div>
    <hr>
    {{-- <div class="col-12 col-sm-8 col-md-4">
        <label for="" class="mb-2">Cari Data</label>
        <form action="{{ url('/user') }}" method="get">
            <div class="input-group">
                <input type="text" class="form-control ml-2" name="cari" placeholder="Kata Kunci" required>
                <button type="submit" class="btn btn-primary"><i class='bx bx-search-alt-2'></i> Cari</button>
                <a href="{{ url('/user') }}" class="btn btn-danger">Batal</a>
            </div>
        </form>
    </div> --}}
    <div class="col-12 col-md-12 text-center">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        @can('admin-user')
                            <th>No</th>
                        @endcan
                        <th>Nama</th>
                        <th>Waktu Daftar</th>
                        <th>Verifikasi Email</th>
                        @can('admin-user')
                            <th>Block</th>
                        @endcan
                        <th>Pengaturan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $value)
                        <tr>
                            @can('admin-user')
                                <td>{{ $data->firstItem() + $key }}</td>
                            @endcan
                            <td>
                                {{ $value->name }}
                                <div class="small">
                                    Email : {{ $value->email }}
                                </div>
                            </td>
                            <td>{{ $value->created_at->isoFormat('dddd, D MMMM Y') }}</td>
                            <td>
                                @if ($value->email_verified_at)
                                    <h4>
                                        <span class="badge bg-success">Terverifikasi</span>
                                    </h4>
                                @else
                                    <h4>
                                        <span class="badge bg-warning">Belum Terverifikasi</span>
                                    </h4>
                                @endif
                            </td>
                            @can('admin-user')
                                <td>
                                    <form action="{{ url('/user/' . $value->id . '/toggle-block') }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit"
                                            style="background: none; border: none; padding: 0; cursor: pointer;">
                                            @if ($value->blocked_at != null)
                                                <h4>
                                                    <span class="badge bg-danger">IYA</span>
                                                </h4>
                                            @else
                                                <h4>
                                                    <span class="badge bg-success">TIDAK</span>
                                                </h4>
                                            @endif
                                        </button>
                                    </form>
                                </td>
                            @endcan
                            <td>
                                <a href="{{ url('/user/' . $value->id . '/edit') }}" class="btn btn-warning">Edit</a>
                                <a href="{{ url('/user/' . $value->id . '/delete') }}" class="btn btn-danger">Hapus</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
