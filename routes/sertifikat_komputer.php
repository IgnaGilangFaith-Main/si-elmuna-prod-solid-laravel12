<?php

use App\Http\Controllers\SertifikatKomputerController;
use Illuminate\Support\Facades\Route;

// ==================== SERTIFIKAT KOMPUTER ====================
Route::middleware(['auth', 'verified', 'blocked'])->group(function () {
    Route::get('/sertifikat/komputer', [SertifikatKomputerController::class, 'index']);
    Route::get('/sertifikat/tambah/komputer/{id}', [SertifikatKomputerController::class, 'create']);
    Route::post('/tambah-sertifikat/komputer', [SertifikatKomputerController::class, 'store']);
    Route::get('/sertifikat/komputer/edit/{id}', [SertifikatKomputerController::class, 'edit']);
    Route::put('/edit-sertifikat/komputer/{id}', [SertifikatKomputerController::class, 'update']);
    Route::get('/sertifikat/komputer/cetak/{id}/sertifikat', [SertifikatKomputerController::class, 'cetak_sertifikat']);
    Route::get('/sertifikat/komputer/cetak/{id}/print-depan', [SertifikatKomputerController::class, 'print_depan']);
    Route::get('/sertifikat/komputer/cetak/{id}/nilai', [SertifikatKomputerController::class, 'cetak_nilai']);
    Route::get('/sertifikat/komputer/hapus/{id}', [SertifikatKomputerController::class, 'delete']);
    Route::delete('/destroy-sertifikat/komputer/{id}', [SertifikatKomputerController::class, 'destroy']);
});
