<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditKuitansiRequest;
use App\Http\Requests\TambahKuitansiRequest;
use App\Models\Kuitansi;
use App\Models\Masuk;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class KuitansiController extends Controller
{
    /**
     * Menampilkan data dengan fungsi pencarian
     */
    public function index(Request $request)
    {
        $cari = $request->cari;

        /** Kolom-kolom yang bisa dicari */
        $kolomCari = [
            'nama',
            'guna_byr1',
            'guna_byr2',
            'guna_byr3',
            'jumlah',
            'penerima',
        ];

        if (! empty($cari)) {
            $data = Kuitansi::latest()
                ->where(function ($query) use ($cari, $kolomCari) {
                    foreach ($kolomCari as $kolom) {
                        $query->orWhere($kolom, 'LIKE', '%'.$cari.'%');
                    }
                })
                ->paginate(20)
                ->onEachSide(2)
                ->appends(request()->query());
        } else {
            $data = Kuitansi::latest()->paginate(20)->onEachSide(2);
        }

        return view('admin.kuitansi.index', ['data' => $data]);
    }

    /**
     * Menampilkan form tambah kuitansi berdasarkan data pemasukan
     */
    public function create($id)
    {
        $masuk = Masuk::findOrFail($id);

        return view('admin.kuitansi.tambah', ['data' => $masuk]);
    }

    /**
     * Menyimpan data kuitansi baru
     */
    public function store(TambahKuitansiRequest $request)
    {
        $validated = $request->validated();

        if (isset($validated['created_at'])) {
            $validated['created_at'] = Carbon::parse($validated['created_at']);
        }

        Kuitansi::create($validated);

        sweetalert()->success('Tambah Data Berhasil!');

        return redirect('/kuitansi');
    }

    /**
     * Menampilkan form edit data
     */
    public function edit($id)
    {
        $kuitansi = Kuitansi::findOrFail($id);

        return view('admin.kuitansi.edit', ['data' => $kuitansi]);
    }

    /**
     * Memperbarui data yang sudah ada
     */
    public function update($id, EditKuitansiRequest $request)
    {
        $validated = $request->validated();

        // if (isset($validated['created_at'])) {
        //     $validated['created_at'] = Carbon::parse($validated['created_at']);
        // }

        $kuitansi = Kuitansi::findOrFail($id);
        $kuitansi->update($validated);

        sweetalert()->success('Update Data Berhasil!');

        return redirect('/kuitansi');
    }

    /**
     * Mencetak kuitansi
     */
    public function cetak($id)
    {
        $kuitansi = Kuitansi::findOrFail($id);

        return view('admin.kuitansi.cetak', ['data' => $kuitansi]);
    }

    /**
     * Menampilkan konfirmasi hapus data
     */
    public function delete($id)
    {
        $kuitansi = Kuitansi::findOrFail($id);

        return view('admin.kuitansi.hapus', ['data' => $kuitansi]);
    }

    /**
     * Menghapus data kuitansi
     */
    public function destroy($id)
    {
        $kuitansi = Kuitansi::findOrFail($id);
        $kuitansi->delete();

        sweetalert()->success('Hapus Data Berhasil!');

        return redirect('/kuitansi');
    }
}
