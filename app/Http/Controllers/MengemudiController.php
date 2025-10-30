<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditMengemudiRequest;
use App\Http\Requests\TambahMengemudiRequest;
use App\Models\Mengemudi;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MengemudiController extends Controller
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
    private function prosessPaket($paket, $mode = 'store')
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
                return (string) $decoded;
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
        return view('pendaftaran.mengemudi');
    }

    /**
     * Menyimpan data pendaftaran baru
     */
    public function store(TambahMengemudiRequest $request)
    {
        $validated = $request->validated();

        // Parse paket dari array ke JSON string
        $validated['paket'] = $this->prosessPaket($validated['paket']);

        Mengemudi::create($validated);

        sweetalert()->success('Anda ['.$validated['nama'].'] Berhasil Mendaftar!');

        return redirect('/daftar_mengemudi');
    }

    /**
     * Menampilkan data dengan fungsi pencarian
     */
    public function index(Request $request)
    {
        $cari = $request->cari;

        // Kolom-kolom yang bisa dicari
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
            $data = Mengemudi::orderBy('id', 'desc')
                ->where(function ($query) use ($cari, $kolomCari) {
                    foreach ($kolomCari as $kolom) {
                        $query->orWhere($kolom, 'LIKE', '%'.$cari.'%');
                    }
                })
                ->paginate(25)
                ->onEachSide(2)
                ->appends(request()->query());
        } else {
            $data = Mengemudi::latest()->paginate(25);
        }

        return view('admin.mengemudi.mengemudi', ['data' => $data]);
    }

    /**
     * Filter data berdasarkan tanggal
     */
    public function filterData(Request $request)
    {
        $start_date = $request->tgl_awal;
        $end_date = $request->tgl_akhir;

        $data = Mengemudi::orderBy('id', 'desc')
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->paginate(25)
            ->onEachSide(2)
            ->appends(request()->query());

        return view('admin.mengemudi.mengemudi', ['data' => $data]);
    }

    /**
     * Menampilkan form edit data
     */
    public function edit($id)
    {
        $data = Mengemudi::findOrFail($id);

        return view('admin.mengemudi.edit-mengemudi', ['data' => $data]);
    }

    /**
     * Memperbarui data yang sudah ada
     */
    public function update($id, EditMengemudiRequest $request)
    {
        $validated = $request->validated();

        // Parse paket dari array ke JSON string
        $validated['paket'] = $this->prosessPaket($validated['paket']);

        $mengemudi = Mengemudi::findOrFail($id);
        $mengemudi->update($validated);

        sweetalert()->success('Update Data Berhasil!');

        return redirect('/data_mengemudi');
    }

    /**
     * Menampilkan konfirmasi hapus data
     */
    public function delete($id)
    {
        $data = Mengemudi::findOrFail($id);

        return view('admin.mengemudi.hapus-mengemudi', ['data' => $data]);
    }

    /**
     * Menghapus data (soft delete)
     */
    public function destroy($id)
    {
        $mengemudi = Mengemudi::findOrFail($id);
        $mengemudi->delete();

        sweetalert()->success('Hapus Data Berhasil!');

        return redirect('/data_mengemudi');
    }

    /**
     * Menampilkan data yang sudah dihapus
     */
    public function deletedMengemudi()
    {
        $data = Mengemudi::onlyTrashed()->get();

        return view('admin.mengemudi.data-terhapus', ['data' => $data]);
    }

    /**
     * Memulihkan data yang sudah dihapus
     */
    public function restoreData($id)
    {
        Mengemudi::withTrashed()->where('id', $id)->restore();

        sweetalert()->success('Restore Data Berhasil!');

        return redirect('/data_mengemudi');
    }

    // Menampilkan konfirmasi hapus permanen
    public function deletePermanen($id)
    {
        $data = Mengemudi::withTrashed()->findOrFail($id);

        return view('admin.mengemudi.hapus-permanen', ['data' => $data]);
    }

    /**
     * Menghapus data secara permanen
     */
    public function forceDelete($id)
    {
        Mengemudi::withTrashed()->findOrFail($id)->forceDelete();

        sweetalert()->success('Data Berhasil Dihapus!');

        return redirect('/data_mengemudi/terhapus');
    }

    /**
     * Mengekspor data ke file Excel
     */
    public function export(Request $request)
    {
        $start_date = $request->tgl_awal;
        $end_date = $request->tgl_akhir;

        // Membuat query dasar untuk filter tanggal
        $baseQuery = Mengemudi::orderBy('id', 'desc')
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date);

        $data = $baseQuery->get();

        // Menghitung jumlah berdasarkan jenis kelamin
        $laki_laki = (clone $baseQuery)->where('jk', 'Laki-laki')->count();
        $perempuan = (clone $baseQuery)->where('jk', 'Perempuan')->count();

        // Membuat spreadsheet baru
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom Excel
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
        $filename = 'Laporan Daftar Peserta Mengemudi '.$start_date.' sampai '.$end_date.'.xlsx';

        // Mengisi data ke Excel
        foreach ($data as $item) {
            $rowData = [
                'A' => $no++,
                'B' => "'".($item->nik ?? ''),
                'C' => "'".($item->nisn ?? ''),
                'D' => $item->nama ?? '',
                'E' => $item->tempat_lahir ?? '',
                'F' => $this->formatTanggal($item->tanggal_lahir ?? null),
                'G' => $item->jk ?? '',
                'H' => $item->alamat ?? '',
                'I' => $item->kecamatan ?? '',
                'J' => $item->kabupaten ?? '',
                'K' => $item->kode_pos ?? '',
                'L' => $item->agama ?? '',
                'M' => $item->status ?? '',
                'N' => $item->nama_ibu ?? '',
                'O' => $item->nama_ayah ?? '',
                'P' => "'".($item->telepon ?? ''),
                'Q' => $item->email ?? '',
                'R' => ! empty($item->created_at) ? $item->created_at->format('d/m/Y') : '-',
                'S' => $this->formatTanggal($item->tgl_mulai ?? null),
                'T' => $this->formatTanggal($item->tgl_selesai ?? null),
                'U' => $this->prosessPaket($item->paket ?? null),
            ];

            foreach ($rowData as $col => $val) {
                $sheet->setCellValue($col.$rows, $val);
            }
            $rows++;
        }

        // Set jumlah berdasarkan gender
        $sheet->setCellValue('V2', $laki_laki);
        $sheet->setCellValue('W2', $perempuan);

        // Download Excel file
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
