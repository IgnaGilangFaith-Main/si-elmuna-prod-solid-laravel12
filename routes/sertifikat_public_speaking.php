<?php

use App\Http\Controllers\SertifikatPublicSpeakingController;
use Illuminate\Support\Facades\Route;

// ==================== SERTIFIKAT PUBLIC SPEAKING ====================
Route::middleware(['auth', 'verified', 'blocked'])->group(function () {
    Route::get('/sertifikat/public-speaking', [SertifikatPublicSpeakingController::class, 'index']);
    Route::get('/sertifikat/tambah/public-speaking/{id}', [SertifikatPublicSpeakingController::class, 'create']);
    Route::post('/tambah-sertifikat/public-speaking', [SertifikatPublicSpeakingController::class, 'store']);
    Route::get('/sertifikat/public-speaking/edit/{id}', [SertifikatPublicSpeakingController::class, 'edit']);
    Route::put('/edit-sertifikat/public-speaking/{id}', [SertifikatPublicSpeakingController::class, 'update']);
    Route::get('/sertifikat/public-speaking/cetak/{id}/sertifikat', [SertifikatPublicSpeakingController::class, 'cetak_sertifikat']);
    Route::get('/sertifikat/public-speaking/cetak/{id}/print-depan', [SertifikatPublicSpeakingController::class, 'print_depan']);
    Route::get('/sertifikat/public-speaking/cetak/{id}/nilai', [SertifikatPublicSpeakingController::class, 'cetak_nilai']);
    Route::get('/sertifikat/public-speaking/hapus/{id}', [SertifikatPublicSpeakingController::class, 'delete']);
    Route::delete('/destroy-sertifikat/public-speaking/{id}', [SertifikatPublicSpeakingController::class, 'destroy']);
});
