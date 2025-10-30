<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditDesainGrafisRequest;
use App\Http\Requests\TambahDesainGrafisRequest;
use App\Models\DesainGrafis;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DesainGrafisController extends Controller
{
    /**
     * Mengubah format tanggal menjadi dd/mm/yyyy
     */
    private function formatTanggal($tanggal)
    {
        if (empty($tanggal)) {
            return '-';
        }

        try {
            $dateObj = new DateTime($tanggal);

            return $dateObj->format('d/m/Y');
        } catch (Exception $e) {
            return '-';
        }
    }

    /**
     * Memproses data paket kursus (array/JSON)
     */
    private function prosessPaket($paket)
    {
        if (empty($paket)) {
            return '-';
        }

        // Untuk data dari form (array)
        if (is_array($paket)) {
            return json_encode($paket);
        }

        // Untuk tampilan (JSON string ke text)
        $decoded = json_decode($paket, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            if (is_array($decoded)) {
                return implode(', ', $decoded);
            } elseif (is_object($decoded)) {
                return implode(', ', (array) $decoded);
            } else {
                return $decoded;
            }
        }

        // Return nilai asli jika bukan JSON
        return $paket;
    }

    /**
     * Menampilkan form pendaftaran
     */
    public function create()
    {
        return view('pendaftaran.desain_grafis');
    }

    /**
     * Menyimpan data pendaftaran baru
     */
    public function store(TambahDesainGrafisRequest $request)
    {
        $validated = $request->validated();

        // Parse paket dari array ke JSON string
        if (isset($validated['paket'])) {
            $validated['paket'] = $this->prosessPaket($validated['paket']);
        }

        DesainGrafis::create($validated);

        sweetalert()->success('Anda ['.$validated['nama'].'] Berhasil Mendaftar!');

        return redirect('/daftar_desain_grafis');
    }

    /**
     * Menampilkan data dengan fungsi pencarian
     */
    public function index(Request $request)
    {
        $cari = $request->cari;

        /** Kolom-kolom yang bisa dicari */
        $kolomCari = [
            'nik',
            'nisn',
            'nama',
            'tempat_lahir',
            'tanggal_lahir',
            'jk',
            'alamat',
            'kecamatan',
            'kabupaten',
            'agama',
            'status',
            'nama_ibu',
            'nama_ayah',
            'telepon',
            'email',
            'paket',
        ];

        if (! empty($cari)) {
            $data = DesainGrafis::orderBy('id', 'desc')
                ->where(function ($query) use ($cari, $kolomCari) {
                    foreach ($kolomCari as $kolom) {
                        $query->orWhere($kolom, 'LIKE', "%{$cari}%");
                    }
                })
                ->paginate(25)
                ->onEachSide(2)
                ->appends(request()->query());
        } else {
            $data = DesainGrafis::latest()->paginate(25);
        }

        return view('admin.desain_grafis.desain_grafis', ['data' => $data]);
    }

    /**
     * Filter data berdasarkan tanggal
     */
    public function filterData(Request $request)
    {
        $start_date = $request->tgl_awal;
        $end_date = $request->tgl_akhir;

        $data = DesainGrafis::orderBy('id', 'desc')
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->paginate(25)
            ->onEachSide(2)
            ->appends(request()->query());

        return view('admin.desain_grafis.desain_grafis', ['data' => $data]);
    }

    /**
     * Menampilkan form edit data
     */
    public function edit($id)
    {
        $data = DesainGrafis::findOrFail($id);

        return view('admin.desain_grafis.edit-desain_grafis', ['data' => $data]);
    }

    /**
     * Memperbarui data yang sudah ada
     */
    public function update($id, EditDesainGrafisRequest $request)
    {
        $validated = $request->validated();

        // Parse paket dari array ke JSON string
        if (isset($validated['paket'])) {
            $validated['paket'] = $this->prosessPaket($validated['paket']);
        }

        $desainGrafis = DesainGrafis::findOrFail($id);
        $desainGrafis->update($validated);

        sweetalert()->success('Update Data Berhasil!');

        return redirect('/data_desain_grafis');
    }

    /**
     * Menampilkan konfirmasi hapus data
     */
    public function delete($id)
    {
        $data = DesainGrafis::findOrFail($id);

        return view('admin.desain_grafis.hapus-desain_grafis', ['data' => $data]);
    }

    /**
     * Menghapus data (soft delete)
     */
    public function destroy($id)
    {
        $desainGrafis = DesainGrafis::findOrFail($id);
        $desainGrafis->delete();

        sweetalert()->success('Hapus Data Berhasil!');

        return redirect('/data_desain_grafis');
    }

    /**
     * Menampilkan data yang sudah dihapus
     */
    public function deletedDesainGrafis()
    {
        $data = DesainGrafis::onlyTrashed()->get();

        return view('admin.desain_grafis.data-terhapus', ['data' => $data]);
    }

    /**
     * Memulihkan data yang sudah dihapus
     */
    public function restoreData($id)
    {
        DesainGrafis::withTrashed()->where('id', $id)->restore();

        sweetalert()->success('Restore Data Berhasil!');

        return redirect('/data_desain_grafis');
    }

    /**
     * Menampilkan konfirmasi hapus permanen
     */
    public function deletePermanen($id)
    {
        $data = DesainGrafis::withTrashed()->findOrFail($id);

        return view('admin.desain_grafis.hapus-permanen', ['data' => $data]);
    }

    /**
     * Menghapus data secara permanen
     */
    public function forceDelete($id)
    {
        DesainGrafis::withTrashed()->findOrFail($id)->forceDelete();

        sweetalert()->success('Berhasil Hapus Data Secara Permanen!');

        return redirect('/data_desain_grafis/terhapus');
    }

    /**
     * Mengekspor data ke file Excel
     */
    public function export(Request $request)
    {
        $start_date = $request->tgl_awal;
        $end_date = $request->tgl_akhir;

        /** Membuat query dasar untuk filter tanggal */
        $baseQuery = DesainGrafis::orderBy('id', 'desc')
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date);

        $data = $baseQuery->get();

        // Menghitung jumlah berdasarkan jenis kelamin
        $laki_laki = (clone $baseQuery)->where('jk', 'Laki-laki')->count();
        $perempuan = (clone $baseQuery)->where('jk', 'Perempuan')->count();

        /** Membuat spreadsheet baru */
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        /** Header kolom Excel */
        $headerKolom = [
            'A1' => 'No',
            'B1' => 'NIK',
            'C1' => 'NISN',
            'D1' => 'Nama',
            'E1' => 'Tempat Lahir',
            'F1' => 'Tanggal Lahir',
            'G1' => 'Jenis Kelamin',
            'H1' => 'Alamat',
            'I1' => 'Kecamatan',
            'J1' => 'Kabupaten',
            'K1' => 'Kode Pos',
            'L1' => 'Agama',
            'M1' => 'Status',
            'N1' => 'Nama Ibu',
            'O1' => 'Nama Ayah',
            'P1' => 'No. WA',
            'Q1' => 'Email',
            'R1' => 'Tanggal Mendaftar',
            'S1' => 'Tanggal Mulai Kursus',
            'T1' => 'Tanggal Selesai Kursus',
            'U1' => 'Paket',
            'V1' => 'Jumlah Peserta Laki-laki',
            'W1' => 'Jumlah Peserta Perempuan',
        ];

        // Set header
        foreach ($headerKolom as $sel => $nilai) {
            $sheet->setCellValue($sel, $nilai);
        }

        $no = 1;
        $rows = 2;
        $filename = 'Laporan Daftar Peserta Desain Grafis '.$start_date.' sampai '.$end_date.'.xlsx';

        // Mengisi data ke Excel
        foreach ($data as $item) {
            $sheet->setCellValue('A'.$rows, $no++);
            $sheet->setCellValue('B'.$rows, "'".($item->nik ?? ''));
            $sheet->setCellValue('C'.$rows, "'".($item->nisn ?? ''));
            $sheet->setCellValue('D'.$rows, $item->nama ?? '');
            $sheet->setCellValue('E'.$rows, $item->tempat_lahir ?? '');
            $sheet->setCellValue('F'.$rows, $this->formatTanggal($item->tanggal_lahir));
            $sheet->setCellValue('G'.$rows, $item->jk ?? '');
            $sheet->setCellValue('H'.$rows, $item->alamat ?? '');
            $sheet->setCellValue('I'.$rows, $item->kecamatan ?? '');
            $sheet->setCellValue('J'.$rows, $item->kabupaten ?? '');
            $sheet->setCellValue('K'.$rows, $item->kode_pos ?? '');
            $sheet->setCellValue('L'.$rows, $item->agama ?? '');
            $sheet->setCellValue('M'.$rows, $item->status ?? '');
            $sheet->setCellValue('N'.$rows, $item->nama_ibu ?? '');
            $sheet->setCellValue('O'.$rows, $item->nama_ayah ?? '');
            $sheet->setCellValue('P'.$rows, "'".($item->telepon ?? ''));
            $sheet->setCellValue('Q'.$rows, $item->email ?? '');
            $sheet->setCellValue('R'.$rows, $item->created_at ? $item->created_at->format('d/m/Y') : '-');
            $sheet->setCellValue('S'.$rows, $this->formatTanggal($item->tgl_mulai ?? ''));
            $sheet->setCellValue('T'.$rows, $this->formatTanggal($item->tgl_selesai ?? ''));
            $sheet->setCellValue('U'.$rows, $this->prosessPaket($item->paket));

            $rows++;
        }

        // Set jumlah berdasarkan gender
        $sheet->setCellValue('V2', $laki_laki);
        $sheet->setCellValue('W2', $perempuan);

        /** Download Excel file */
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
