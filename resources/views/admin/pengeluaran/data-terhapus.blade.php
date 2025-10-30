@extends('layout.admin')
@section('title', 'Restore Pengeluaran')
@section('content')
    <center>
        <h1 class="my-3">Data Pengeluaran Yang Dihapus</h1>
    </center>
    <div class="my-3">
        <a href="{{ url('/pengeluaran') }}" class="btn btn-secondary">Kembali</a>
    </div>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="align-middle">
                    <th>No.</th>
                    <th>Keterangan Pengeluaran</th>
                    <th>Tanggal</th>
                    <th>Jumlah Pengeluaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1 + ($sql->currentPage() - 1) * $sql->perPage();
                @endphp
                @if ($sql->count() > 0)
                    @foreach ($sql as $datum)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $datum->ket_pengeluaran }}</td>
                            <td>{{ $datum->created_at->isoFormat('DD MMMM Y') }}</td>
                            <td>Rp. {{ $datum->jumlah_pengeluaran }} ,-</td>
                            <td>
                                <a href="{{ url('/restore-pengeluaran/' . $datum->id) }}"
                                    class="btn btn-secondary">Restore</a>
                                <a href="{{ url('/pengeluaran/hapus_permanen/' . $datum->id) }}" class="btn btn-danger my-2">
                                    Hapus Permanen
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">
                            <center>
                                <h3>Data Kosong</h3>
                            </center>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        {{ $sql->links() }}
    </div>
@endsection
