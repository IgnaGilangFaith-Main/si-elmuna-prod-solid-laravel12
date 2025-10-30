<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SertifikatDesainGrafisController;


// ==================== SERTIFIKAT DESAIN GRAFIS ====================
Route::middleware('auth')->group(function () {
    Route::get('/sertifikat/desain-grafis', [SertifikatDesainGrafisController::class, 'index']);
    Route::get('/sertifikat/tambah/desain-grafis/{id}', [SertifikatDesainGrafisController::class, 'create']);
    Route::post('/tambah-sertifikat/desain-grafis', [SertifikatDesainGrafisController::class, 'store']);
    Route::get('/sertifikat/desain-grafis/edit/{id}', [SertifikatDesainGrafisController::class, 'edit']);
    Route::put('/edit-sertifikat/desain-grafis/{id}', [SertifikatDesainGrafisController::class, 'update']);
    Route::get('/sertifikat/desain-grafis/cetak/{id}/sertifikat', [SertifikatDesainGrafisController::class, 'cetak_sertifikat']);
    Route::get('/sertifikat/desain-grafis/cetak/{id}/print-depan', [SertifikatDesainGrafisController::class, 'print_depan']);
    Route::get('/sertifikat/desain-grafis/cetak/{id}/nilai', [SertifikatDesainGrafisController::class, 'cetak_nilai']);
    Route::get('/sertifikat/desain-grafis/hapus/{id}', [SertifikatDesainGrafisController::class, 'delete']);
    Route::delete('/destroy-sertifikat/desain-grafis/{id}', [SertifikatDesainGrafisController::class, 'destroy']);
});