<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditSertifikatRequest;
use App\Http\Requests\TambahSertifikatRequest;
use App\Models\Komputer;
use App\Models\SertifikatKomputer;
use Illuminate\Http\Request;

class SertifikatKomputerController extends Controller
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

        return SertifikatKomputer::orderBy('id', 'desc')
            ->where(function ($query) use ($keyword, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $query->orWhere($column, 'LIKE', '%'.$keyword.'%');
                }
            })
            ->get();
    }

    /**
     * Menampilkan daftar sertifikat komputer dengan fitur pencarian
     */
    public function index(Request $request)
    {
        $cari = $request->cari;

        if (! empty($cari)) {
            $data = $this->searchCertificates($cari);
        } else {
            $data = SertifikatKomputer::latest()->get();
        }

        return view('admin.sertifikat.komputer.index', ['data' => $data]);
    }

    /**
     * Menampilkan form untuk membuat sertifikat baru
     */
    public function create($id)
    {
        $data = Komputer::findOrFail($id);

        return view('admin.sertifikat.komputer.tambah', ['data' => $data]);
    }

    /**
     * Menyimpan sertifikat baru ke database
     */
    public function store(TambahSertifikatRequest $request)
    {
        $validated = $request->validated();
        SertifikatKomputer::create($validated);

        sweetalert()->success('Tambah Data Berhasil!');

        return redirect('/sertifikat/komputer');
    }

    /**
     * Menampilkan form untuk mengedit sertifikat
     */
    public function edit($id)
    {
        $data = SertifikatKomputer::findOrFail($id);

        return view('admin.sertifikat.komputer.edit', ['data' => $data]);
    }

    /**
     * Memperbarui data sertifikat di database
     */
    public function update($id, EditSertifikatRequest $request)
    {
        $validated = $request->validated();
        $sertifikat = SertifikatKomputer::findOrFail($id);
        $sertifikat->update($validated);

        sweetalert()->success('Update Data Berhasil!');

        return redirect('/sertifikat/komputer');
    }

    /**
     * Menampilkan konfirmasi penghapusan sertifikat
     */
    public function delete($id)
    {
        $data = SertifikatKomputer::findOrFail($id);

        return view('admin.sertifikat.komputer.hapus', ['data' => $data]);
    }

    /**
     * Menghapus sertifikat dari database
     */
    public function destroy($id)
    {
        $sertifikat = SertifikatKomputer::findOrFail($id);
        $sertifikat->delete();

        sweetalert()->success('Hapus Data Berhasil!');

        return redirect('/sertifikat/komputer');
    }

    /**
     * Mencetak sertifikat
     */
    public function cetak_sertifikat($id)
    {
        $data = SertifikatKomputer::findOrFail($id);

        return view('admin.sertifikat.komputer.cetak-sertifikat', ['data' => $data]);
    }

    /**
     * Mencetak nilai sertifikat
     */
    public function cetak_nilai($id)
    {
        $data = SertifikatKomputer::findOrFail($id);

        return view('admin.sertifikat.komputer.cetak-nilai', ['data' => $data]);
    }

    /**
     * Mencetak bagian depan sertifikat
     */
    public function print_depan($id)
    {
        $data = SertifikatKomputer::findOrFail($id);

        return view('admin.sertifikat.komputer.cetak-print-depan', ['data' => $data]);
    }
}
