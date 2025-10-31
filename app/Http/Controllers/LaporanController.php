<?php

namespace App\Http\Controllers;

use App\Models\Keluar;
use App\Models\Masuk;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanController extends Controller
{
    /**
     * Mendapatkan range tanggal berdasarkan filter
     */
    private function getDateRange($filter)
    {
        switch ($filter) {
            case 'hari_ini':
                return [Carbon::today(), Carbon::today()];
            case 'kemarin':
                return [Carbon::yesterday(), Carbon::yesterday()];
            case 'minggu_ini':
                return [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
            case 'minggu_lalu':
                return [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()];
            case 'bulan_ini':
                return [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
            case 'bulan_lalu':
                return [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()];
            case 'tahun_ini':
                return [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()];
            case 'tahun_lalu':
                return [Carbon::now()->subYear()->startOfYear(), Carbon::now()->subYear()->endOfYear()];
            default:
                return null;
        }
    }

    /**
     * Mendapatkan data yang sudah difilter berdasarkan tanggal
     */
    private function getFilteredData($filter)
    {
        $dateRange = $this->getDateRange($filter);

        if ($dateRange) {
            [$startDate, $endDate] = $dateRange;

            if ($filter === 'hari_ini' || $filter === 'kemarin') {
                $masukData = Masuk::whereDate('created_at', $startDate)->latest()->get();
                $keluarData = Keluar::whereDate('created_at', $startDate)->latest()->get();
            } elseif (in_array($filter, ['bulan_ini', 'bulan_lalu'])) {
                $masukData = Masuk::whereMonth('created_at', $startDate->month)
                    ->whereYear('created_at', $startDate->year)->latest()->get();
                $keluarData = Keluar::whereMonth('created_at', $startDate->month)
                    ->whereYear('created_at', $startDate->year)->latest()->get();
            } elseif (in_array($filter, ['tahun_ini', 'tahun_lalu'])) {
                $masukData = Masuk::whereYear('created_at', $startDate->year)->latest()->get();
                $keluarData = Keluar::whereYear('created_at', $startDate->year)->latest()->get();
            } else {
                $masukData = Masuk::whereBetween('created_at', [$startDate, $endDate])->latest()->get();
                $keluarData = Keluar::whereBetween('created_at', [$startDate, $endDate])->latest()->get();
            }
        } else {
            $masukData = Masuk::latest()->get();
            $keluarData = Keluar::latest()->get();
        }

        return [$masukData, $keluarData];
    }

    /**
     * Menghitung total pemasukan, pengeluaran, dan saldo
     */
    private function calculateTotals($pemasukan, $pengeluaran)
    {
        $totalMasuk = $pemasukan->sum('jumlah_pemasukan');
        $totalKeluar = $pengeluaran->sum('jumlah_pengeluaran');
        $total = $totalMasuk - $totalKeluar;

        return [$totalMasuk, $totalKeluar, $total];
    }

    /**
     * Mendapatkan label filter untuk tampilan
     */
    private function getFilterLabel($filter)
    {
        switch ($filter) {
            case 'hari_ini':
                return 'Hari Ini tanggal '.Carbon::today()->format('d-m-Y');
            case 'kemarin':
                return 'Kemarin tanggal '.Carbon::yesterday()->format('d-m-Y');
            case 'minggu_ini':
                return 'Minggu Ini tanggal '.Carbon::now()->startOfWeek()->format('d-m-Y').
                    ' sampai '.Carbon::now()->endOfWeek()->format('d-m-Y');
            case 'minggu_lalu':
                return 'Minggu Lalu tanggal '.Carbon::now()->subWeek()->startOfWeek()->format('d-m-Y').
                    ' sampai '.Carbon::now()->subWeek()->endOfWeek()->format('d-m-Y');
            case 'bulan_ini':
                return 'Bulan '.Carbon::now()->month.' tahun '.Carbon::now()->year;
            case 'bulan_lalu':
                return 'Bulan '.Carbon::now()->subMonth()->month.' tahun '.Carbon::now()->year;
            case 'tahun_ini':
                return 'Tahun Ini '.Carbon::now()->year;
            case 'tahun_lalu':
                return 'Tahun Lalu '.Carbon::now()->subYear()->year;
            default:
                return 'Semua Data';
        }
    }

    /**
     * Set header untuk export Excel
     */
    private function setExportHeaders($sheet)
    {
        $sheet->setCellValue('A1', 'Pemasukan');
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A2', 'No');
        $sheet->setCellValue('B2', 'Keterangan Pemasukan');
        $sheet->setCellValue('C2', 'Tanggal Pemasukan');
        $sheet->setCellValue('D2', 'Jumlah Pemasukan (Rp)');
        $sheet->setCellValue('E2', 'Total Pemasukan (Rp)');

        $sheet->setCellValue('G1', 'Pengeluaran');
        $sheet->mergeCells('G1:K1');
        $sheet->setCellValue('G2', 'No');
        $sheet->setCellValue('H2', 'Keterangan Pengeluaran');
        $sheet->setCellValue('I2', 'Tanggal Pengeluaran');
        $sheet->setCellValue('J2', 'Jumlah Pengeluaran (Rp)');
        $sheet->setCellValue('K2', 'Total Pengeluaran (Rp)');

        $sheet->setCellValue('M2', 'Total Pendapatan');
    }

    /**
     * Mengisi data ke Excel
     */
    private function fillExportData($sheet, $masukData, $keluarData, $totals)
    {
        [$totalMasuk, $totalKeluar, $total] = $totals;

        $rowMasuk = 3;
        $rowKeluar = 3;
        $noMasuk = 1;
        $noKeluar = 1;

        /** Mengisi data pemasukan */
        foreach ($masukData as $data) {
            $sheet->setCellValue('A'.$rowMasuk, $noMasuk++);
            $sheet->setCellValue('B'.$rowMasuk, $data->ket_pemasukan);
            $sheet->setCellValue('C'.$rowMasuk, $data->created_at->format('d/m/Y'));
            $sheet->setCellValue('D'.$rowMasuk, $data->jumlah_pemasukan);
            $rowMasuk++;
        }

        /** Mengisi data pengeluaran */
        foreach ($keluarData as $data) {
            $sheet->setCellValue('G'.$rowKeluar, $noKeluar++);
            $sheet->setCellValue('H'.$rowKeluar, $data->ket_pengeluaran);
            $sheet->setCellValue('I'.$rowKeluar, $data->created_at->format('d/m/Y'));
            $sheet->setCellValue('J'.$rowKeluar, $data->jumlah_pengeluaran);
            $rowKeluar++;
        }

        /** Mengisi total */
        $sheet->setCellValue('E3', $totalMasuk);
        $sheet->setCellValue('K3', $totalKeluar);
        $sheet->setCellValue('M3', $total);
    }

    /**
     * Menampilkan halaman laporan dengan filter
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->can('admin-keuangan')) {
            $tanggal = $request->filter_tanggal;

            [$masukData, $keluarData] = $this->getFilteredData($tanggal);
            [$totalMasuk, $totalKeluar, $total] = $this->calculateTotals($masukData, $keluarData);

            return view('admin.laporan.index', [
                'masuk' => $masukData,
                'keluar' => $keluarData,
                'totalMasuk' => $totalMasuk,
                'totalKeluar' => $totalKeluar,
                'total' => $total,
                'tanggal' => $tanggal,
            ]);
        } else {
            return abort(403);
        }

    }

    /**
     * Mengekspor laporan ke file Excel
     */
    public function export(Request $request)
    {
        $user = Auth::user();

        if ($user->can('admin-keuangan')) {
            $ekspor = $request->ekspor;

            [$masukData, $keluarData] = $this->getFilteredData($ekspor);
            [$totalMasuk, $totalKeluar, $total] = $this->calculateTotals($masukData, $keluarData);
            $filter = $this->getFilterLabel($ekspor);

            $spreadsheet = new Spreadsheet;
            $filename = 'Rekap Pendapatan '.$filter.'.xlsx';
            $sheet = $spreadsheet->getActiveSheet();

            /** Set headers */
            $this->setExportHeaders($sheet);

            /** Fill data */
            $this->fillExportData($sheet, $masukData, $keluarData, [$totalMasuk, $totalKeluar, $total]);

            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        } else {
            return abort(403);
        }

    }
}
