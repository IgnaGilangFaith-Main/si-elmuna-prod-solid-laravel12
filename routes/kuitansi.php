<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KuitansiController;


// ==================== KUITANSI ====================
Route::middleware('auth')->group(function () {
    Route::get('/kuitansi', [KuitansiController::class, 'index']);
    Route::get('/kuitansi/tambah/{id}', [KuitansiController::class, 'create']);
    Route::post('/tambah-kuitansi', [KuitansiController::class, 'store']);
    Route::get('/kuitansi/edit/{id}', [KuitansiController::class, 'edit']);
    Route::put('/edit-kuitansi/{id}', [KuitansiController::class, 'update']);
    Route::get('/kuitansi/cetak/{id}', [KuitansiController::class, 'cetak']);
    Route::get('/kuitansi/hapus/{id}', [KuitansiController::class, 'delete']);
    Route::delete('/destroy-kuitansi/{id}', [KuitansiController::class, 'destroy']);
});
