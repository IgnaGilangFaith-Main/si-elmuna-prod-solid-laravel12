<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditSertifikatRequest;
use App\Http\Requests\TambahSertifikatRequest;
use App\Models\PublicSpeaking;
use App\Models\SertifikatPublicSpeaking;
use Illuminate\Http\Request;

class SertifikatPublicSpeakingController extends Controller
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

        return SertifikatPublicSpeaking::orderBy('id', 'desc')
            ->where(function ($query) use ($keyword, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $query->orWhere($column, 'LIKE', '%'.$keyword.'%');
                }
            })
            ->get();
    }

    /**
     * Menampilkan daftar sertifikat public speaking dengan fitur pencarian
     */
    public function index(Request $request)
    {
        $cari = $request->cari;

        if (! empty($cari)) {
            $data = $this->searchCertificates($cari);
        } else {
            $data = SertifikatPublicSpeaking::latest()->get();
        }

        return view('admin.sertifikat.public_speaking.index', ['data' => $data]);
    }

    /**
     * Menampilkan form untuk membuat sertifikat baru
     */
    public function create($id)
    {
        $data = PublicSpeaking::findOrFail($id);

        return view('admin.sertifikat.public_speaking.tambah', ['data' => $data]);
    }

    /**
     * Menyimpan sertifikat baru ke database
     */
    public function store(TambahSertifikatRequest $request)
    {
        $validated = $request->validated();
        SertifikatPublicSpeaking::create($validated);

        sweetalert()->success('Tambah Data Berhasil!');

        return redirect('/sertifikat/public-speaking');
    }

    /**
     * Menampilkan form untuk mengedit sertifikat
     */
    public function edit($id)
    {
        $data = SertifikatPublicSpeaking::findOrFail($id);

        return view('admin.sertifikat.public_speaking.edit', ['data' => $data]);
    }

    /**
     * Memperbarui data sertifikat di database
     */
    public function update($id, EditSertifikatRequest $request)
    {
        $validated = $request->validated();
        $sertifikat = SertifikatPublicSpeaking::findOrFail($id);
        $sertifikat->update($validated);

        sweetalert()->success('Update Data Berhasil!');

        return redirect('/sertifikat/public-speaking');
    }

    /**
     * Menampilkan konfirmasi penghapusan sertifikat
     */
    public function delete($id)
    {
        $data = SertifikatPublicSpeaking::findOrFail($id);

        return view('admin.sertifikat.public_speaking.hapus', ['data' => $data]);
    }

    /**
     * Menghapus sertifikat dari database
     */
    public function destroy($id)
    {
        $sertifikat = SertifikatPublicSpeaking::findOrFail($id);
        $sertifikat->delete();

        sweetalert()->success('Hapus Data Berhasil!');

        return redirect('/sertifikat/public-speaking');
    }

    /**
     * Mencetak sertifikat
     */
    public function cetak_sertifikat($id)
    {
        $data = SertifikatPublicSpeaking::findOrFail($id);

        return view('admin.sertifikat.public_speaking.cetak-sertifikat', ['data' => $data]);
    }

    /**
     * Mencetak nilai sertifikat
     */
    public function cetak_nilai($id)
    {
        $data = SertifikatPublicSpeaking::findOrFail($id);

        return view('admin.sertifikat.public_speaking.cetak-nilai', ['data' => $data]);
    }

    /**
     * Mencetak bagian depan sertifikat
     */
    public function print_depan($id)
    {
        $data = SertifikatPublicSpeaking::findOrFail($id);

        return view('admin.sertifikat.public_speaking.cetak-print-depan', ['data' => $data]);
    }
}
