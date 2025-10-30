<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditSertifikatRequest;
use App\Http\Requests\TambahSertifikatRequest;
use App\Models\BahasaInggris;
use App\Models\SertifikatBahasaInggris;
use Illuminate\Http\Request;

class SertifikatBahasaInggrisController extends Controller
{
    /**
     * Mendapatkan kolom-kolom yang bisa dicari
     */
    private function getSearchableColumns()
    {
        return [
            'no_sertifikat',
            'nama',
            'tempat_lahir',
            'nis',
            'program',
            'mapel1',
            'mapel2',
            'mapel3',
            'mapel4',
            'mapel5',
            'nilai1',
            'nilai2',
            'nilai3',
            'nilai4',
            'nilai5',
        ];
    }

    /**
     * Mencari data sertifikat berdasarkan keyword
     */
    private function searchCertificates($keyword)
    {
        $searchableColumns = $this->getSearchableColumns();

        return SertifikatBahasaInggris::orderBy('id', 'desc')
            ->where(function ($query) use ($keyword, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $query->orWhere($column, 'LIKE', '%'.$keyword.'%');
                }
            })
            ->get();
    }

    /**
     * Menampilkan daftar sertifikat bahasa inggris dengan fitur pencarian
     */
    public function index(Request $request)
    {
        $cari = $request->cari;

        if (! empty($cari)) {
            $data = $this->searchCertificates($cari);
        } else {
            $data = SertifikatBahasaInggris::latest()->get();
        }

        return view('admin.sertifikat.bahasa_inggris.index', ['data' => $data]);
    }

    /**
     * Menampilkan form untuk membuat sertifikat baru
     */
    public function create($id)
    {
        $data = BahasaInggris::findOrFail($id);

        return view('admin.sertifikat.bahasa_inggris.tambah', ['data' => $data]);
    }

    /**
     * Menyimpan sertifikat baru ke database
     */
    public function store(TambahSertifikatRequest $request)
    {
        $validated = $request->validated();
        SertifikatBahasaInggris::create($validated);

        sweetalert()->success('Tambah Data Berhasil!');

        return redirect('/sertifikat/bahasa-inggris');
    }

    /**
     * Menampilkan form untuk mengedit sertifikat
     */
    public function edit($id)
    {
        $data = SertifikatBahasaInggris::findOrFail($id);

        return view('admin.sertifikat.bahasa_inggris.edit', ['data' => $data]);
    }

    /**
     * Memperbarui data sertifikat di database
     */
    public function update($id, EditSertifikatRequest $request)
    {
        $validated = $request->validated();
        $sertifikat = SertifikatBahasaInggris::findOrFail($id);
        $sertifikat->update($validated);

        sweetalert()->success('Update Data Berhasil!');

        return redirect('/sertifikat/bahasa-inggris');
    }

    /**
     * Menampilkan konfirmasi penghapusan sertifikat
     */
    public function delete($id)
    {
        $data = SertifikatBahasaInggris::findOrFail($id);

        return view('admin.sertifikat.bahasa_inggris.hapus', ['data' => $data]);
    }

    /**
     * Menghapus sertifikat dari database
     */
    public function destroy($id)
    {
        $sertifikat = SertifikatBahasaInggris::findOrFail($id);
        $sertifikat->delete();

        sweetalert()->success('Hapus Data Berhasil!');

        return redirect('/sertifikat/bahasa-inggris');
    }

    /**
     * Mencetak sertifikat
     */
    public function cetak_sertifikat($id)
    {
        $data = SertifikatBahasaInggris::findOrFail($id);

        return view('admin.sertifikat.bahasa_inggris.cetak-sertifikat', ['data' => $data]);
    }

    /**
     * Mencetak nilai sertifikat
     */
    public function cetak_nilai($id)
    {
        $data = SertifikatBahasaInggris::findOrFail($id);

        return view('admin.sertifikat.bahasa_inggris.cetak-nilai', ['data' => $data]);
    }

    /**
     * Mencetak bagian depan sertifikat
     */
    public function print_depan($id)
    {
        $data = SertifikatBahasaInggris::findOrFail($id);

        return view('admin.sertifikat.bahasa_inggris.cetak-print-depan', ['data' => $data]);
    }
}
