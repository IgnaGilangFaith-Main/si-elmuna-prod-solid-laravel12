<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditSertifikatRequest;
use App\Http\Requests\TambahSertifikatRequest;
use App\Models\SertifikatVideoFoto;
use App\Models\VideoFoto;
use Illuminate\Http\Request;

class SertifikatVideoFotoController extends Controller
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

        return SertifikatVideoFoto::orderBy('id', 'desc')
            ->where(function ($query) use ($keyword, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $query->orWhere($column, 'LIKE', '%'.$keyword.'%');
                }
            })
            ->get();
    }

    /**
     * Menampilkan daftar sertifikat video editing fotografi dengan fitur pencarian
     */
    public function index(Request $request)
    {
        $cari = $request->cari;

        if (! empty($cari)) {
            $data = $this->searchCertificates($cari);
        } else {
            $data = SertifikatVideoFoto::latest()->get();
        }

        return view('admin.sertifikat.video_editing_fotografi.index', ['data' => $data]);
    }

    /**
     * Menampilkan form untuk membuat sertifikat baru
     */
    public function create($id)
    {
        $data = VideoFoto::findOrFail($id);

        return view('admin.sertifikat.video_editing_fotografi.tambah', ['data' => $data]);
    }

    /**
     * Menyimpan sertifikat baru ke database
     */
    public function store(TambahSertifikatRequest $request)
    {
        $validated = $request->validated();
        SertifikatVideoFoto::create($validated);

        sweetalert()->success('Tambah Data Berhasil!');

        return redirect('/sertifikat/video-editing-fotografi');
    }

    /**
     * Menampilkan form untuk mengedit sertifikat
     */
    public function edit($id)
    {
        $data = SertifikatVideoFoto::findOrFail($id);

        return view('admin.sertifikat.video_editing_fotografi.edit', ['data' => $data]);
    }

    /**
     * Memperbarui data sertifikat di database
     */
    public function update($id, EditSertifikatRequest $request)
    {
        $validated = $request->validated();
        $sertifikat = SertifikatVideoFoto::findOrFail($id);
        $sertifikat->update($validated);

        sweetalert()->success('Update Data Berhasil!');

        return redirect('/sertifikat/video-editing-fotografi');
    }

    /**
     * Menampilkan konfirmasi penghapusan sertifikat
     */
    public function delete($id)
    {
        $data = SertifikatVideoFoto::findOrFail($id);

        return view('admin.sertifikat.video_editing_fotografi.hapus', ['data' => $data]);
    }

    /**
     * Menghapus sertifikat dari database
     */
    public function destroy($id)
    {
        $sertifikat = SertifikatVideoFoto::findOrFail($id);
        $sertifikat->delete();

        sweetalert()->success('Hapus Data Berhasil!');

        return redirect('/sertifikat/video-editing-fotografi');
    }

    /**
     * Mencetak sertifikat
     */
    public function cetak_sertifikat($id)
    {
        $data = SertifikatVideoFoto::findOrFail($id);

        return view('admin.sertifikat.video_editing_fotografi.cetak-sertifikat', ['data' => $data]);
    }

    /**
     * Mencetak nilai sertifikat
     */
    public function cetak_nilai($id)
    {
        $data = SertifikatVideoFoto::findOrFail($id);

        return view('admin.sertifikat.video_editing_fotografi.cetak-nilai', ['data' => $data]);
    }

    /**
     * Mencetak bagian depan sertifikat
     */
    public function print_depan($id)
    {
        $data = SertifikatVideoFoto::findOrFail($id);

        return view('admin.sertifikat.video_editing_fotografi.cetak-print-depan', ['data' => $data]);
    }
}
