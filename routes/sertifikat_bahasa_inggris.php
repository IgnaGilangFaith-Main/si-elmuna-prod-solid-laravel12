<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SertifikatBahasaInggrisController;


// ==================== SERTIFIKAT BAHASA INGGRIS ====================
Route::middleware('auth')->group(function () {
    Route::get('/sertifikat/bahasa-inggris', [SertifikatBahasaInggrisController::class, 'index']);
    Route::get('/sertifikat/tambah/bahasa-inggris/{id}', [SertifikatBahasaInggrisController::class, 'create']);
    Route::post('/tambah-sertifikat/bahasa-inggris', [SertifikatBahasaInggrisController::class, 'store']);
    Route::get('/sertifikat/bahasa-inggris/edit/{id}', [SertifikatBahasaInggrisController::class, 'edit']);
    Route::put('/edit-sertifikat/bahasa-inggris/{id}', [SertifikatBahasaInggrisController::class, 'update']);
    Route::get('/sertifikat/bahasa-inggris/cetak/{id}/sertifikat', [SertifikatBahasaInggrisController::class, 'cetak_sertifikat']);
    Route::get('/sertifikat/bahasa-inggris/cetak/{id}/print-depan', [SertifikatBahasaInggrisController::class, 'print_depan']);
    Route::get('/sertifikat/bahasa-inggris/cetak/{id}/nilai', [SertifikatBahasaInggrisController::class, 'cetak_nilai']);
    Route::get('/sertifikat/bahasa-inggris/hapus/{id}', [SertifikatBahasaInggrisController::class, 'delete']);
    Route::delete('/destroy-sertifikat/bahasa-inggris/{id}', [SertifikatBahasaInggrisController::class, 'destroy']);
});