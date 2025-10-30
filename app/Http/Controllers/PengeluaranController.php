<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditKeluarRequest;
use App\Http\Requests\TambahPengeluaranRequest;
use App\Models\Keluar;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PengeluaranController extends Controller
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
     * Mendapatkan data pengeluaran yang sudah difilter
     */
    private function getFilteredData($filter, $paginate = false)
    {
        $dateRange = $this->getDateRange($filter);

        if ($dateRange) {
            [$startDate, $endDate] = $dateRange;

            if ($filter === 'hari_ini' || $filter === 'kemarin') {
                $query = Keluar::whereDate('created_at', $startDate);
            } elseif (in_array($filter, ['bulan_ini', 'bulan_lalu'])) {
                $query = Keluar::whereMonth('created_at', $startDate->month)
                    ->whereYear('created_at', $startDate->year);
            } elseif (in_array($filter, ['tahun_ini', 'tahun_lalu'])) {
                $query = Keluar::whereYear('created_at', $startDate->year);
            } else {
                $query = Keluar::whereBetween('created_at', [$startDate, $endDate]);
            }
        } else {
            $query = Keluar::query();
        }

        $query->latest();

        if ($paginate) {
            return $query->paginate(20)->onEachSide(2)->appends(request()->query());
        } else {
            return $query->get();
        }
    }

    /**
     * Menghitung total pengeluaran
     */
    private function calculateTotal($filter)
    {
        $data = $this->getFilteredData($filter, false);

        return $data->sum('jumlah_pengeluaran');
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
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Keterangan Pengeluaran');
        $sheet->setCellValue('C1', 'Tanggal Pengeluaran');
        $sheet->setCellValue('D1', 'Jumlah Pengeluaran (Rp)');
        $sheet->setCellValue('E1', 'Total Pengeluaran (Rp)');
    }

    /**
     * Mengisi data ke Excel
     */
    private function fillExportData($sheet, $data, $total)
    {
        $row = 2;
        $no = 1;

        foreach ($data as $item) {
            $sheet->setCellValue('A'.$row, $no++);
            $sheet->setCellValue('B'.$row, $item->ket_pengeluaran);
            $sheet->setCellValue('C'.$row, $item->created_at->format('d/m/Y'));
            $sheet->setCellValue('D'.$row, $item->jumlah_pengeluaran);
            $row++;
        }

        $sheet->setCellValue('E2', $total);
    }

    /**
     * Menampilkan data pengeluaran dengan filter
     */
    public function index(Request $request)
    {
        $tanggal = $request->filter_tanggal;

        $data = $this->getFilteredData($tanggal, true);
        /** with pagination */
        $total = $this->calculateTotal($tanggal);
        /** total berdasarkan filter date range */

        return view('admin.pengeluaran.index', [
            'sql' => $data,
            'total' => $total,
            'tanggal' => $tanggal,
        ]);
    }

    /**
     * Menampilkan form tambah pengeluaran
     */
    public function create()
    {
        return view('admin.pengeluaran.tambah-pengeluaran');
    }

    /**
     * Menyimpan data pengeluaran baru
     */
    public function store(TambahPengeluaranRequest $request)
    {
        $validated = $request->validated();

        if (isset($validated['created_at'])) {
            $validated['created_at'] = Carbon::parse($validated['created_at']);
        }

        Keluar::create($validated);

        sweetalert()->success('Tambah Data Berhasil!');

        return redirect('/pengeluaran');
    }

    /**
     * Menampilkan form edit data
     */
    public function edit($id)
    {
        $pengeluaran = Keluar::findOrFail($id);

        return view('admin.pengeluaran.edit-pengeluaran', ['data' => $pengeluaran]);
    }

    /**
     * Memperbarui data yang sudah ada
     */
    public function update($id, EditKeluarRequest $request)
    {
        $validated = $request->validated();

        if (isset($validated['created_at'])) {
            $validated['created_at'] = Carbon::parse($validated['created_at']);
        }

        $pengeluaran = Keluar::findOrFail($id);
        $pengeluaran->update($validated);

        sweetalert()->success('Update Data Berhasil!');

        return redirect('/pengeluaran');
    }

    /**
     * Menampilkan konfirmasi hapus data
     */
    public function delete($id)
    {
        $pengeluaran = Keluar::findOrFail($id);

        return view('admin.pengeluaran.hapus-pengeluaran', ['data' => $pengeluaran]);
    }

    /**
     * Menghapus data (soft delete)
     */
    public function destroy($id)
    {
        $pengeluaran = Keluar::findOrFail($id);
        $pengeluaran->delete();

        sweetalert()->success('Hapus Data Berhasil!');

        return redirect('/pengeluaran');
    }

    /**
     * Menampilkan data yang sudah dihapus
     */
    public function deletedPengeluaran()
    {
        $data = Keluar::onlyTrashed()->latest()->paginate(20);

        return view('admin.pengeluaran.data-terhapus', ['sql' => $data]);
    }

    /**
     * Memulihkan data yang sudah dihapus
     */
    public function restoreData($id)
    {
        Keluar::withTrashed()->where('id', $id)->restore();
        sweetalert()->success('Restore Data Berhasil!');

        return redirect('/pengeluaran');
    }

    /**
     * Menampilkan konfirmasi hapus permanen
     */
    public function deletePermanen($id)
    {
        $pengeluaran = Keluar::withTrashed()->findOrFail($id);

        return view('admin.pengeluaran.hapus_permanen', ['data' => $pengeluaran]);
    }

    /**
     * Menghapus data secara permanen
     */
    public function forceDelete($id)
    {
        Keluar::withTrashed()->findOrFail($id)->forceDelete();
        sweetalert()->success('Berhasil Hapus Data Secara Permanen!');

        return redirect('/pengeluaran/restore');
    }

    /**
     * Mengekspor data ke file Excel
     */
    public function export(Request $request)
    {
        $ekspor = $request->ekspor;

        $data = $this->getFilteredData($ekspor, false);
        $total = $this->calculateTotal($ekspor);
        /** total berdasarkan filter date range */
        $filter = $this->getFilterLabel($ekspor);

        $spreadsheet = new Spreadsheet;
        $filename = 'Laporan Pengeluaran '.$filter.'.xlsx';
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $this->setExportHeaders($sheet);

        // Fill data
        $this->fillExportData($sheet, $data, $total);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
