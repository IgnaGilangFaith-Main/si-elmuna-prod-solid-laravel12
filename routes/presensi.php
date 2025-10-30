<?php

use App\Http\Controllers\PresensiController;
use Illuminate\Support\Facades\Route;

// ==================== PRESENSI ====================
Route::middleware(['auth', 'verified', 'blocked'])->group(function () {
    Route::get('/presensi', [PresensiController::class, 'index']);
    Route::get('scan/{id}', [PresensiController::class, 'scan']);
    Route::get('/presensi/hapus/{id}', [PresensiController::class, 'destroy']);
    Route::get('/get-data-karyawan', [PresensiController::class, 'getDataKaryawan']);
    Route::get('/presensi/tambah', [PresensiController::class, 'create']);
    Route::post('/tambah-presensi', [PresensiController::class, 'store']);
    Route::get('/presensi/filter', [PresensiController::class, 'filterData']);
    Route::post('/presensi/export', [PresensiController::class, 'export']);
});
