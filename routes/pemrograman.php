<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PemrogramanController;


// ==================== PEMROGRAMAN ====================
Route::get('/daftar_pemrograman', [PemrogramanController::class, 'create']);
Route::post('/tambah-pemrograman', [PemrogramanController::class, 'store']);

Route::middleware('auth')->group(function () {
    // Data utama
    Route::get('/data_pemrograman', [PemrogramanController::class, 'index']);
    Route::get('/data_pemrograman/filter', [PemrogramanController::class, 'filterData']);
    Route::post('/data_pemrograman/export', [PemrogramanController::class, 'export']);

    // CRUD
    Route::get('/edit_pemrograman/{id}', [PemrogramanController::class, 'edit']);
    Route::put('/update-pemrograman/{id}', [PemrogramanController::class, 'update']);
    Route::get('/hapus_pemrograman/{id}', [PemrogramanController::class, 'delete']);
    Route::delete('/destroy-pemrograman/{id}', [PemrogramanController::class, 'destroy']);

    // Soft delete & restore
    Route::get('/data_pemrograman/terhapus', [PemrogramanController::class, 'deletedPemrograman']);
    Route::get('/restore-pemrograman/{id}', [PemrogramanController::class, 'restoreData']);
    Route::get('/hapus_permanen_pemrograman/{id}', [PemrogramanController::class, 'deletePermanen']);
    Route::delete('/force-delete-pemrograman/{id}', [PemrogramanController::class, 'forceDelete']);
});