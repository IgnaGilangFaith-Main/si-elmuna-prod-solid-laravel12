<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MengemudiController;


// ==================== MENGEMUDI ====================
Route::get('/daftar_mengemudi', [MengemudiController::class, 'create']);
Route::post('/tambah-mengemudi', [MengemudiController::class, 'store']);

Route::middleware('auth')->group(function () {
    // Data utama
    Route::get('/data_mengemudi', [MengemudiController::class, 'index']);
    Route::get('/data_mengemudi/filter', [MengemudiController::class, 'filterData']);
    Route::post('/data_mengemudi/export', [MengemudiController::class, 'export']);

    // CRUD
    Route::get('/edit_mengemudi/{id}', [MengemudiController::class, 'edit']);
    Route::put('/update-mengemudi/{id}', [MengemudiController::class, 'update']);
    Route::get('/hapus_mengemudi/{id}', [MengemudiController::class, 'delete']);
    Route::delete('/destroy-mengemudi/{id}', [MengemudiController::class, 'destroy']);

    // Soft delete & restore
    Route::get('/data_mengemudi/terhapus', [MengemudiController::class, 'deletedMengemudi']);
    Route::get('/restore-mengemudi/{id}', [MengemudiController::class, 'restoreData']);
    Route::get('/hapus_permanen_mengemudi/{id}', [MengemudiController::class, 'deletePermanen']);
    Route::delete('/force-delete-mengemudi/{id}', [MengemudiController::class, 'forceDelete']);
});