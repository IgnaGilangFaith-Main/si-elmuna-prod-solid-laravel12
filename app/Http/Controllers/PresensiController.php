<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Presensi;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PresensiController extends Controller
{
    // Set header untuk export Excel
    private function setExportHeaders($sheet)
    {
        $headers = ['No', 'Nama', 'Jabatan', 'Waktu Presensi', 'Status', 'Tanggal'];
        $columns = ['A', 'B', 'C', 'D', 'E', 'F'];

        foreach ($headers as $index => $header) {
            $sheet->setCellValue($columns[$index].'1', $header);
        }
    }

    // Mengisi data presensi ke Excel
    private function fillAttendanceData($sheet, $attendanceData)
    {
        $no = 1;
        $rows = 2;

        foreach ($attendanceData as $data) {
            $sheet->setCellValue('A'.$rows, $no++);
            $sheet->setCellValue('B'.$rows, $data->nama);
            $sheet->setCellValue('C'.$rows, $data->jabatan);
            $sheet->setCellValue('D'.$rows, date('H:i', strtotime($data->waktu_presensi)).' WIB');
            $sheet->setCellValue('E'.$rows, $data->status);
            $sheet->setCellValue('F'.$rows, date('d/m/Y', strtotime($data->created_at)));
            $rows++;
        }
    }

    // Menampilkan data presensi dengan fungsi pencarian
    public function index(Request $request)
    {
        $cari = $request->cari;

        // Kolom-kolom yang bisa dicari
        $kolomCari = ['nama', 'jabatan', 'status'];

        if (! empty($cari)) {
            $data = Presensi::orderBy('id', 'desc')
                ->where(function ($query) use ($cari, $kolomCari) {
                    foreach ($kolomCari as $kolom) {
                        $query->orWhere($kolom, 'LIKE', '%'.$cari.'%');
                    }
                })
                ->get();
        } else {
            $data = Presensi::orderBy('id', 'desc')->get();
        }

        return view('admin.presensi.index', ['data' => $data]);
    }

    // Filter data berdasarkan tanggal
    public function filterData(Request $request)
    {
        $startDate = $request->tgl_awal;
        $endDate = $request->tgl_akhir;

        $data = Presensi::orderBy('id', 'desc')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();

        return view('admin.presensi.index', ['data' => $data]);
    }

    // Scan QR Code untuk presensi otomatis
    public function scan($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $attendanceData = [
            'nama' => $karyawan->nama,
            'jabatan' => $karyawan->jabatan,
            'waktu_presensi' => now(),
            'status' => 'Hadir',
        ];

        Presensi::create($attendanceData);

        sweetalert()->info('Presensi Berhasil!');

        return redirect('/presensi');
    }

    // Menampilkan form tambah presensi manual
    public function create()
    {
        $karyawan = Karyawan::get();

        return view('admin.presensi.tambah', ['karyawans' => $karyawan]);
    }

    // API untuk mendapatkan data karyawan via AJAX
    public function getDataKaryawan(Request $request)
    {
        $id = $request->input('id');
        $karyawan = Karyawan::find($id);

        return response()->json($karyawan);
    }

    // Menyimpan data presensi manual
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'jabatan' => 'required',
            'status' => 'required',
        ], [
            'nama.required' => 'Nama wajib diisi!',
            'jabatan.required' => 'Jabatan wajib diisi!',
            'status.required' => 'Status wajib diisi!',
        ]);

        $attendanceData = [
            'nama' => $validated['nama'],
            'jabatan' => $validated['jabatan'],
            'waktu_presensi' => now(),
            'status' => $validated['status'],
        ];

        Presensi::create($attendanceData);

        sweetalert()->info('Presensi Berhasil!');

        return redirect('/presensi');
    }

    // Menghapus data presensi
    public function destroy($id)
    {
        $presensi = Presensi::findOrFail($id);
        $presensi->delete();

        sweetalert()->success('Hapus Data Berhasil!');

        return redirect('/presensi');
    }

    // Mengekspor data presensi ke file Excel
    public function export(Request $request)
    {
        $startDate = $request->tgl_awal;
        $endDate = $request->tgl_akhir;

        $attendanceData = Presensi::orderBy('id', 'desc')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $this->setExportHeaders($sheet);

        // Fill data
        $this->fillAttendanceData($sheet, $attendanceData);

        $namaFile = 'Laporan Presensi Karyawan '.date('Y-m-d', strtotime($startDate)).
                   ' sampai '.date('Y-m-d', strtotime($endDate)).'.xlsx';

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$namaFile.'"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
