<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditSertifikatRequest;
use App\Http\Requests\TambahSertifikatRequest;
use App\Models\DigitalMarketing;
use App\Models\SertifikatDigitalMarketing;
use Illuminate\Http\Request;

class SertifikatDigitalMarketingController extends Controller
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

        return SertifikatDigitalMarketing::orderBy('id', 'desc')
            ->where(function ($query) use ($keyword, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $query->orWhere($column, 'LIKE', '%'.$keyword.'%');
                }
            })
            ->get();
    }

    /**
     * Menampilkan daftar sertifikat digital marketing dengan fitur pencarian
     */
    public function index(Request $request)
    {
        $cari = $request->cari;

        if (! empty($cari)) {
            $data = $this->searchCertificates($cari);
        } else {
            $data = SertifikatDigitalMarketing::latest()->get();
        }

        return view('admin.sertifikat.digital_marketing.index', ['data' => $data]);
    }

    /**
     * Menampilkan form untuk membuat sertifikat baru
     */
    public function create($id)
    {
        $data = DigitalMarketing::findOrFail($id);

        return view('admin.sertifikat.digital_marketing.tambah', ['data' => $data]);
    }

    /**
     * Menyimpan sertifikat baru ke database
     */
    public function store(TambahSertifikatRequest $request)
    {
        $validated = $request->validated();
        SertifikatDigitalMarketing::create($validated);

        sweetalert()->success('Tambah Data Berhasil!');

        return redirect('/sertifikat/digital-marketing');
    }

    /**
     * Menampilkan form untuk mengedit sertifikat
     */
    public function edit($id)
    {
        $data = SertifikatDigitalMarketing::findOrFail($id);

        return view('admin.sertifikat.digital_marketing.edit', ['data' => $data]);
    }

    /**
     * Memperbarui data sertifikat di database
     */
    public function update($id, EditSertifikatRequest $request)
    {
        $validated = $request->validated();
        $sertifikat = SertifikatDigitalMarketing::findOrFail($id);
        $sertifikat->update($validated);

        sweetalert()->success('Update Data Berhasil!');

        return redirect('/sertifikat/digital-marketing');
    }

    /**
     * Menampilkan konfirmasi penghapusan sertifikat
     */
    public function delete($id)
    {
        $data = SertifikatDigitalMarketing::findOrFail($id);

        return view('admin.sertifikat.digital_marketing.hapus', ['data' => $data]);
    }

    /**
     * Menghapus sertifikat dari database
     */
    public function destroy($id)
    {
        $sertifikat = SertifikatDigitalMarketing::findOrFail($id);
        $sertifikat->delete();

        sweetalert()->success('Hapus Data Berhasil!');

        return redirect('/sertifikat/digital-marketing');
    }

    /**
     * Mencetak sertifikat
     */
    public function cetak_sertifikat($id)
    {
        $data = SertifikatDigitalMarketing::findOrFail($id);

        return view('admin.sertifikat.digital_marketing.cetak-sertifikat', ['data' => $data]);
    }

    /**
     * Mencetak nilai sertifikat
     */
    public function cetak_nilai($id)
    {
        $data = SertifikatDigitalMarketing::findOrFail($id);

        return view('admin.sertifikat.digital_marketing.cetak-nilai', ['data' => $data]);
    }

    /**
     * Mencetak bagian depan sertifikat
     */
    public function print_depan($id)
    {
        $data = SertifikatDigitalMarketing::findOrFail($id);

        return view('admin.sertifikat.digital_marketing.cetak-print-depan', ['data' => $data]);
    }
}
