<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicSpeakingController;


// ==================== PUBLIC SPEAKING ====================
Route::get('/daftar_public_speaking', [PublicSpeakingController::class, 'create']);
Route::post('/tambah-public-speaking', [PublicSpeakingController::class, 'store']);

Route::middleware('auth')->group(function () {
    // Data utama
    Route::get('/data_public_speaking', [PublicSpeakingController::class, 'index']);
    Route::get('/data_public_speaking/filter', [PublicSpeakingController::class, 'filterData']);
    Route::post('/data_public_speaking/export', [PublicSpeakingController::class, 'export']);

    // CRUD
    Route::get('/edit_public_speaking/{id}', [PublicSpeakingController::class, 'edit']);
    Route::put('/update-public_speaking/{id}', [PublicSpeakingController::class, 'update']);
    Route::get('/hapus_public_speaking/{id}', [PublicSpeakingController::class, 'delete']);
    Route::delete('/destroy-public_speaking/{id}', [PublicSpeakingController::class, 'destroy']);

    // Soft delete & restore
    Route::get('/data_public_speaking/terhapus', [PublicSpeakingController::class, 'deletedPublicSpeaking']);
    Route::get('/restore-public_speaking/{id}', [PublicSpeakingController::class, 'restoreData']);
    Route::get('/hapus_permanen_public_speaking/{id}', [PublicSpeakingController::class, 'deletePermanen']);
    Route::delete('/force-delete-public_speaking/{id}', [PublicSpeakingController::class, 'forceDelete']);
});