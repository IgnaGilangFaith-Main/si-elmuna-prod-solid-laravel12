@extends('layout.admin')
@section('title', 'Presensi')
@section('content')
    <h1 class="text-center">Data Presensi</h1>
    <div class="my-3">
        <a href="{{ url('/presensi/tambah') }}" class="btn btn-primary">Tambah Data</a>
    </div>
    <hr>
    <div class="col-12 col-sm-8 col-md-4">
        <label for="" class="mb-2">Cari Data</label>
        <form action="{{ url('/presensi') }}" method="get">
            <div class="input-group">
                <input type="text" class="form-control ml-2" name="cari" placeholder="Kata Kunci" required>
                <button type="submit" class="btn btn-primary"><i class='bx bx-search-alt-2'></i> Cari</button>
                <a href="{{ url('/presensi') }}" class="btn btn-danger">Batal</a>
            </div>
        </form>
    </div>
    <hr>
    <div class="col-md-6">
        <label class="mb-2">Filter Tanggal Presensi</label>
        <form action="{{ url('/presensi/filter') }}" method="get">
            <div class="input-group">
                <span class="input-group-text">Dari Tanggal : </span>
                <input type="date" class="form-control" name="tgl_awal" required>
                <span class="input-group-text">Sampai Tanggal : </span>
                <input type="date" name="tgl_akhir" class="form-control" required>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ url('/presensi') }}" class="btn btn-danger">Batal</a>
            </div>
        </form>
    </div>
    <hr>
    <div class="col-md-6">
        <label class="mb-2">Export Data ke Excel</label>
        <form action="{{ url('/presensi/export') }}" method="post">
            @csrf
            <div class="input-group">
                <span class="input-group-text">Dari Tanggal : </span>
                <input type="date" class="form-control" name="tgl_awal" required>
                <span class="input-group-text">Sampai Tanggal : </span>
                <input type="date" name="tgl_akhir" class="form-control" required>
                <button type="submit" class="btn btn-success">Export</button>
            </div>
        </form>
    </div>
    <hr>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Waktu Presensi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if (count($data) > 0)
                    @foreach ($data as $datum)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $datum->nama }}</td>
                            <td>{{ $datum->jabatan }}</td>
                            <td>{{ $datum->waktu_presensi->isoFormat('HH:mm') }} WIB,
                                {{ $datum->waktu_presensi->isoFormat('D MMMM Y') }}
                            </td>
                            <td class="text-center">
                                @if ($datum->status == 'Hadir')
                                    <label class="badge bg-success">Hadir</label>
                                @elseif ($datum->status == 'Izin')
                                    <label class="badge bg-warning">Izin</label>
                                @elseif ($datum->status == 'Sakit')
                                    <label class="badge bg-warning">Sakit</label>
                                @elseif ($datum->status == 'Alpha')
                                    <label class="badge bg-danger">Alpha</label>
                                @endif
                            </td>
                            <td>
                                <center>
                                    <a href="{{ url('/presensi/hapus/' . $datum->id) }}"
                                        class="btn btn-danger my-2">Hapus</a>
                                </center>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="22">
                            <center>
                                <h3>Data Kosong</h3>
                            </center>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
