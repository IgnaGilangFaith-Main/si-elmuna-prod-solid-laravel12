<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PemasukanController;


// ==================== PEMASUKAN ====================
Route::middleware('auth')->group(function () {
    Route::get('/pemasukan', [PemasukanController::class, 'index']);
    Route::get('/pemasukan/tambah', [PemasukanController::class, 'create']);
    Route::post('/tambah-pemasukan', [PemasukanController::class, 'store']);
    Route::get('/pemasukan/edit/{id}', [PemasukanController::class, 'edit']);
    Route::put('/edit-pemasukan/{id}', [PemasukanController::class, 'update']);
    Route::get('/pemasukan/hapus/{id}', [PemasukanController::class, 'delete']);
    Route::delete('/destroy-pemasukan/{id}', [PemasukanController::class, 'destroy']);
    Route::get('/pemasukan/restore', [PemasukanController::class, 'deletedPemasukan']);
    Route::get('/restore-pemasukan/{id}', [PemasukanController::class, 'restoreData']);
    Route::get('/pemasukan/hapus_permanen/{id}', [PemasukanController::class, 'deletePermanen']);
    Route::delete('/force_delete-pemasukan/{id}', [PemasukanController::class, 'forceDelete']);
    Route::post('/pemasukan/export', [PemasukanController::class, 'export']);
});