<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BahasaInggrisController;


// ==================== BAHASA INGGRIS ====================
Route::get('/daftar_bahasa_inggris', [BahasaInggrisController::class, 'create']);
Route::post('/tambah-bahasa_inggris', [BahasaInggrisController::class, 'store']);

Route::middleware('auth')->group(function () {
    // Data utama
    Route::get('/data_bahasa_inggris', [BahasaInggrisController::class, 'index']);
    Route::get('/data_bahasa_inggris/filter', [BahasaInggrisController::class, 'filterData']);
    Route::post('/data_bahasa_inggris/export', [BahasaInggrisController::class, 'export']);

    // CRUD
    Route::get('/edit_bahasa_inggris/{id}', [BahasaInggrisController::class, 'edit']);
    Route::put('/update-bahasa_inggris/{id}', [BahasaInggrisController::class, 'update']);
    Route::get('/hapus_bahasa_inggris/{id}', [BahasaInggrisController::class, 'delete']);
    Route::delete('/destroy-bahasa_inggris/{id}', [BahasaInggrisController::class, 'destroy']);

    // Soft delete & restore
    Route::get('/data_bahasa_inggris/terhapus', [BahasaInggrisController::class, 'deletedBahasaInggris']);
    Route::get('/restore-bahasa_inggris/{id}', [BahasaInggrisController::class, 'restoreData']);
    Route::get('/hapus_permanen_bahasa_inggris/{id}', [BahasaInggrisController::class, 'deletePermanen']);
    Route::delete('/force-delete-bahasa_inggris/{id}', [BahasaInggrisController::class, 'forceDelete']);
});