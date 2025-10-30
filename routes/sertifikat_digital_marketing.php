<?php

use App\Http\Controllers\SertifikatDigitalMarketingController;
use Illuminate\Support\Facades\Route;

// ==================== SERTIFIKAT DIGITAL MARKETING ====================
Route::middleware(['auth', 'verified', 'blocked'])->group(function () {
    Route::get('/sertifikat/digital-marketing', [SertifikatDigitalMarketingController::class, 'index']);
    Route::get('/sertifikat/tambah/digital-marketing/{id}', [SertifikatDigitalMarketingController::class, 'create']);
    Route::post('/tambah-sertifikat/digital-marketing', [SertifikatDigitalMarketingController::class, 'store']);
    Route::get('/sertifikat/digital-marketing/edit/{id}', [SertifikatDigitalMarketingController::class, 'edit']);
    Route::put('/edit-sertifikat/digital-marketing/{id}', [SertifikatDigitalMarketingController::class, 'update']);
    Route::get('/sertifikat/digital-marketing/cetak/{id}/sertifikat', [SertifikatDigitalMarketingController::class, 'cetak_sertifikat']);
    Route::get('/sertifikat/digital-marketing/cetak/{id}/print-depan', [SertifikatDigitalMarketingController::class, 'print_depan']);
    Route::get('/sertifikat/digital-marketing/cetak/{id}/nilai', [SertifikatDigitalMarketingController::class, 'cetak_nilai']);
    Route::get('/sertifikat/digital-marketing/hapus/{id}', [SertifikatDigitalMarketingController::class, 'delete']);
    Route::delete('/destroy-sertifikat/digital-marketing/{id}', [SertifikatDigitalMarketingController::class, 'destroy']);
});
