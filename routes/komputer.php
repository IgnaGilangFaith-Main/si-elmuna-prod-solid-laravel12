<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KomputerController;


// ==================== KOMPUTER ====================
Route::get('/daftar_komputer', [KomputerController::class, 'create']);
Route::post('/tambah-komputer', [KomputerController::class, 'store']);

Route::middleware('auth')->group(function () {
    // Data utama
    Route::get('/data_komputer', [KomputerController::class, 'index']);
    Route::get('/data_komputer/filter', [KomputerController::class, 'filterData']);
    Route::post('/data_komputer/export', [KomputerController::class, 'export']);

    // CRUD
    Route::get('/edit_komputer/{id}', [KomputerController::class, 'edit']);
    Route::put('/update-komputer/{id}', [KomputerController::class, 'update']);
    Route::get('/hapus_komputer/{id}', [KomputerController::class, 'delete']);
    Route::delete('/destroy-komputer/{id}', [KomputerController::class, 'destroy']);

    // Soft delete & restore
    Route::get('/data_komputer/terhapus', [KomputerController::class, 'deletedKomputer']);
    Route::get('/restore-komputer/{id}', [KomputerController::class, 'restoreData']);
    Route::get('/hapus_permanen_komputer/{id}', [KomputerController::class, 'deletePermanen']);
    Route::delete('/force-delete-komputer/{id}', [KomputerController::class, 'forceDelete']);
});