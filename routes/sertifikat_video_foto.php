<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SertifikatVideoFotoController;


// ==================== SERTIFIKAT VIDEO EDITING & FOTOGRAFI ====================
Route::middleware('auth')->group(function () {
    Route::get('/sertifikat/video-editing-fotografi', [SertifikatVideoFotoController::class, 'index']);
    Route::get('/sertifikat/tambah/video-editing-fotografi/{id}', [SertifikatVideoFotoController::class, 'create']);
    Route::post('/tambah-sertifikat/video-editing-fotografi', [SertifikatVideoFotoController::class, 'store']);
    Route::get('/sertifikat/video-editing-fotografi/edit/{id}', [SertifikatVideoFotoController::class, 'edit']);
    Route::put('/edit-sertifikat/video-editing-fotografi/{id}', [SertifikatVideoFotoController::class, 'update']);
    Route::get('/sertifikat/video-editing-fotografi/cetak/{id}/sertifikat', [SertifikatVideoFotoController::class, 'cetak_sertifikat']);
    Route::get('/sertifikat/video-editing-fotografi/cetak/{id}/print-depan', [SertifikatVideoFotoController::class, 'print_depan']);
    Route::get('/sertifikat/video-editing-fotografi/cetak/{id}/nilai', [SertifikatVideoFotoController::class, 'cetak_nilai']);
    Route::get('/sertifikat/video-editing-fotografi/hapus/{id}', [SertifikatVideoFotoController::class, 'delete']);
    Route::delete('/destroy-sertifikat/video-editing-fotografi/{id}', [SertifikatVideoFotoController::class, 'destroy']);
});