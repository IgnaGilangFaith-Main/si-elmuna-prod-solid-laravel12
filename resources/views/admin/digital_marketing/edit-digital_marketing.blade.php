@extends('layout.admin')
@section('title', 'Elmuna - Edit Digital Marketing')
@section('content')
    @if (Session::has('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <center>
                <h3>Form Edit Peserta Kursus Digital Marketing</h3>
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
            @php
                $paket = json_decode($data->paket);
            @endphp
            <form action="{{ url('/update-digital_marketing/' . $data->id) }}" method="post">
                @csrf
                @method('put')
                <center>
                    <h5>Identitas Peserta</h5>
                </center>
                <div class="mb-3">
                    <label for="nik" class="form-label">NIK</label>
                    <input type="text" name="nik" id="nik" class="form-control" value="{{ $data->nik }}">
                </div>
                <div class="mb-3">
                    <label for="nisn" class="form-label">NISN</label>
                    <input type="text" name="nisn" id="nisn" class="form-control" value="{{ $data->nisn }}">
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" class="form-control" value="{{ $data->nama }}">
                </div>
                <div class="mb-3">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control"
                        value="{{ $data->tempat_lahir }}">
                </div>
                <div class="mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control"
                        value="{{ $data->tanggal_lahir->format('Y-m-d') }}">
                </div>
                <div class="mb-3">
                    <label for="jk" class="form-label">Jenis Kelamin</label>
                    <select name="jk" id="jk" class="form-select select2"
                        data-placeholder="Pilih Jenis Kelamin">
                        <option value="Laki-laki" {{ $data->jk == 'Laki-laki' ? 'selected' : null }}>Laki-laki</option>
                        <option value="Perempuan" {{ $data->jk == 'Perempuan' ? 'selected' : null }}>Perempuan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input type="text" name="alamat" id="alamat" class="form-control" value="{{ $data->alamat }}">
                </div>
                <div class="mb-3">
                    <label for="kecamatan" class="form-label">Kecamatan</label>
                    <input type="text" class="form-control" name="kecamatan" id="kecamatan"
                        value="{{ $data->kecamatan }}">
                </div>
                <div class="mb-3">
                    <label for="kabupaten" class="form-label">Kabupaten</label>
                    <input type="text" class="form-control" name="kabupaten" id="kabupaten"
                        value="{{ $data->kabupaten }}">
                </div>
                <div class="mb-3">
                    <label for="kode_pos" class="form-label">Kode Pos</label>
                    <input type="text" class="form-control" name="kode_pos" id="kode_pos"
                        value="{{ $data->kode_pos }}">
                </div>
                <div class="mb-3">
                    <label for="agama" class="form-label">Agama</label>
                    <input type="text" class="form-control" name="agama" id="agama" value="{{ $data->agama }}">
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status Pekerjaan</label>
                    <select name="status" id="status" class="form-select select2"
                        data-placeholder="Pilih Jenis Kelamin">
                        <option value="Bekerja" {{ $data->status == 'Bekerja' ? 'selected' : null }}>Bekerja</option>
                        <option value="Pelajar/Mahasiswa" {{ $data->status == 'Pelajar/Mahasiswa' ? 'selected' : null }}>
                            Pelajar/Mahasiswa</option>
                        <option value="Tidak Bekerja" {{ $data->status == 'Tidak Bekerja' ? 'selected' : null }}>Tidak
                            Bekerja</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="nama_ibu" class="form-label">Nama Ibu</label>
                    <input type="text" name="nama_ibu" id="nama_ibu" class="form-control"
                        value="{{ $data->nama_ibu }}">
                </div>
                <div class="mb-3">
                    <label for="nama_ayah" class="form-label">Nama Ayah</label>
                    <input type="text" name="nama_ayah" id="nama_ayah" class="form-control"
                        value="{{ $data->nama_ayah }}">
                </div>
                <div class="mb-3">
                    <label for="telepon" class="form-label">No. WA</label>
                    <input type="text" name="telepon" id="telepon" class="form-control"
                        aria-describedby="teleponHelp" value="{{ $data->telepon }}" placeholder="Ganti 08 menjadi 628">
                    <div id="teleponHelp" class="form-text">Misal 08131111222 menjadi 6281311112222</div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="{{ $data->email }}">
                </div>
                <div class="mb-3">
                    <label for="paket" class="form-label">Pilih Paket</label>
                    <div class="form-check">
                        <input type="checkbox" name="paket[]" id="paket_pemula" class="form-check-input"
                            value="PAKET PEMULA" {{ in_array('PAKET PEMULA', $paket) ? 'checked' : '' }}>
                        <label for="paket_pemula" class="form-check-label">PAKET PEMULA</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="paket[]" id="paket_mahir" class="form-check-input"
                            value="PAKET MAHIR" {{ in_array('PAKET MAHIR', $paket) ? 'checked' : '' }}>
                        <label for="paket_mahir" class="form-check-label">PAKET MAHIR</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="paket[]" id="paket_pelajar" class="form-check-input"
                            value="PAKET PELAJAR" {{ in_array('PAKET PELAJAR', $paket) ? 'checked' : '' }}>
                        <label for="paket_pelajar" class="form-check-label">PAKET PELAJAR</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="paket[]" id="paket_hemat" class="form-check-input"
                            value="PAKET HEMAT" {{ in_array('PAKET HEMAT', $paket) ? 'checked' : '' }}>
                        <label for="paket_hemat" class="form-check-label">PAKET HEMAT</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="tgl_mulai" class="form-label">Tanggal Mulai Kursus</label>
                    <input type="date" class="form-control" name="tgl_mulai" id="tgl_mulai"
                        value="{{ old('tgl_mulai', $data->tgl_mulai ? $data->tgl_mulai->format('Y-m-d') : '') }}">
                </div>
                <div class="mb-3">
                    <label for="tgl_selesai" class="form-label">Tanggal Selesai Kursus</label>
                    <input type="date" class="form-control" name="tgl_selesai" id="tgl_selesai"
                        value="{{ old('tgl_selesai', $data->tgl_selesai ? $data->tgl_selesai->format('Y-m-d') : '') }}">
                </div>
                <div class="my-2">
                    <center>
                        <a href="{{ url('/data_digital_marketing') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-success ms-2">Kirim</button>
                    </center>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(".select2").select2({
            theme: "bootstrap-5",
        });
    </script>
@endpush
