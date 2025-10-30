<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SertifikatMengemudiController;


// ==================== SERTIFIKAT MENGEMUDI ====================
Route::middleware('auth')->group(function () {
    Route::get('/sertifikat/mengemudi', [SertifikatMengemudiController::class, 'index']);
    Route::get('/sertifikat/tambah/mengemudi/{id}', [SertifikatMengemudiController::class, 'create']);
    Route::post('/tambah-sertifikat/mengemudi', [SertifikatMengemudiController::class, 'store']);
    Route::get('/sertifikat/mengemudi/edit/{id}', [SertifikatMengemudiController::class, 'edit']);
    Route::put('/edit-sertifikat/mengemudi/{id}', [SertifikatMengemudiController::class, 'update']);
    Route::get('/sertifikat/mengemudi/cetak/{id}/sertifikat', [SertifikatMengemudiController::class, 'cetak_sertifikat']);
    Route::get('/sertifikat/mengemudi/cetak/{id}/print-depan', [SertifikatMengemudiController::class, 'print_depan']);
    Route::get('/sertifikat/mengemudi/cetak/{id}/nilai', [SertifikatMengemudiController::class, 'cetak_nilai']);
    Route::get('/sertifikat/mengemudi/hapus/{id}', [SertifikatMengemudiController::class, 'delete']);
    Route::delete('/destroy-sertifikat/mengemudi/{id}', [SertifikatMengemudiController::class, 'destroy']);
});
