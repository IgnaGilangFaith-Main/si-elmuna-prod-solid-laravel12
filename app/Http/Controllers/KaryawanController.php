<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditKaryawanRequest;
use App\Http\Requests\TambahKaryawanRequest;
use App\Models\Karyawan;
use Exception;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class KaryawanController extends Controller
{
    /**
     * Menampilkan data dengan fungsi pencarian
     */
    public function index(Request $request)
    {
        $cari = $request->input('cari');

        /** Kolom-kolom yang bisa dicari */
        $kolomCari = ['nama', 'jabatan'];

        if (! empty($cari)) {
            $data = Karyawan::orderBy('id', 'desc')
                ->where(function ($query) use ($cari, $kolomCari) {
                    foreach ($kolomCari as $kolom) {
                        $query->orWhere($kolom, 'LIKE', '%'.$cari.'%');
                    }
                })
                ->get();
        } else {
            $data = Karyawan::latest()->get();
        }

        return view('admin.karyawan.index', ['data' => $data]);
    }

    /**
     * Menampilkan form tambah karyawan
     */
    public function create()
    {
        return view('admin.karyawan.tambah');
    }

    /**
     * Menyimpan data karyawan baru
     */
    public function store(TambahKaryawanRequest $request)
    {
        $validated = $request->validated();
        $validated['foto_karyawan'] = $this->prosessFotoKaryawan($request->file('foto_karyawan'));

        Karyawan::create($validated);
        sweetalert()->success('Berhasil Menambah Data!');

        return redirect('/karyawan');
    }

    /**
     * Generate QR Code untuk karyawan
     */
    public function qrCode($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $urlQrCode = url('/scan/'.$karyawan->id);
        $qrCode = QrCode::size(200)
            ->errorCorrection('H')
            ->generate($urlQrCode);

        return view('admin.karyawan.qr', [
            'data' => $karyawan,
            'qrCode' => $qrCode,
        ]);
    }

    /**
     * Menampilkan form edit data
     */
    public function edit($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        return view('admin.karyawan.edit', ['data' => $karyawan]);
    }

    /**
     * Memperbarui data yang sudah ada
     */
    public function update($id, EditKaryawanRequest $request)
    {
        $karyawan = Karyawan::findOrFail($id);
        $validated = $request->validated();
        $validated['foto_karyawan'] = $this->prosessFotoKaryawan(
            $request->file('foto_karyawan'),
            $karyawan->foto_karyawan
        );

        $karyawan->update($validated);
        sweetalert()->success('Update Data Berhasil!');

        return redirect('/karyawan');
    }

    /**
     * Menghapus data karyawan
     */
    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        /** Hapus file foto jika ada */
        if ($karyawan->foto_karyawan && file_exists(public_path('foto_karyawan/'.$karyawan->foto_karyawan))) {
            unlink(public_path('foto_karyawan/'.$karyawan->foto_karyawan));
        }

        $karyawan->delete();
        sweetalert()->success('Hapus Data Berhasil!');

        return redirect('/karyawan');
    }

    /**
     * Menampilkan konfirmasi hapus data
     */
    public function delete($id)
    {
        $data = Karyawan::findOrFail($id);

        return view('admin.karyawan.hapus', ['data' => $data]);
    }

    /**
     * Memproses upload file foto
     */
    private function prosessFotoKaryawan($file, $oldFile = null)
    {
        if (! $file) {
            return $oldFile;
        }

        try {
            // Delete old file if exists
            if ($oldFile && file_exists(public_path('foto_karyawan/'.$oldFile))) {
                unlink(public_path('foto_karyawan/'.$oldFile));
            }

            // Generate unique filename
            $filename = time().'_'.$file->getClientOriginalName();

            // Move file to public directory
            $file->move(public_path('foto_karyawan'), $filename);

            return $filename;
        } catch (Exception $e) {
            // Return old file if upload fails
            return $oldFile;
        }
    }
}
