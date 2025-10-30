<?php

use App\Http\Controllers\SertifikatPemrogramanController;
use Illuminate\Support\Facades\Route;

// ==================== SERTIFIKAT PEMROGRAMAN ====================
Route::middleware(['auth', 'verified', 'blocked'])->group(function () {
    Route::get('/sertifikat/pemrograman', [SertifikatPemrogramanController::class, 'index']);
    Route::get('/sertifikat/tambah/pemrograman/{id}', [SertifikatPemrogramanController::class, 'create']);
    Route::post('/tambah-sertifikat/pemrograman', [SertifikatPemrogramanController::class, 'store']);
    Route::get('/sertifikat/pemrograman/edit/{id}', [SertifikatPemrogramanController::class, 'edit']);
    Route::put('/edit-sertifikat/pemrograman/{id}', [SertifikatPemrogramanController::class, 'update']);
    Route::get('/sertifikat/pemrograman/cetak/{id}/sertifikat', [SertifikatPemrogramanController::class, 'cetak_sertifikat']);
    Route::get('/sertifikat/pemrograman/cetak/{id}/print-depan', [SertifikatPemrogramanController::class, 'print_depan']);
    Route::get('/sertifikat/pemrograman/cetak/{id}/nilai', [SertifikatPemrogramanController::class, 'cetak_nilai']);
    Route::get('/sertifikat/pemrograman/hapus/{id}', [SertifikatPemrogramanController::class, 'delete']);
    Route::delete('/destroy-sertifikat/pemrograman/{id}', [SertifikatPemrogramanController::class, 'destroy']);
});
