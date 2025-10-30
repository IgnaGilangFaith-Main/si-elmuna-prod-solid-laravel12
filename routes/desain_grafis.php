<?php

use App\Http\Controllers\DesainGrafisController;
use Illuminate\Support\Facades\Route;

// ==================== DESAIN GRAFIS ====================
Route::get('/daftar_desain_grafis', [DesainGrafisController::class, 'create']);
Route::post('/tambah-desain_grafis', [DesainGrafisController::class, 'store']);

Route::middleware(['auth', 'verified', 'blocked'])->group(function () {
    // Data utama
    Route::get('/data_desain_grafis', [DesainGrafisController::class, 'index']);
    Route::get('/data_desain_grafis/filter', [DesainGrafisController::class, 'filterData']);
    Route::post('/data_desain_grafis/export', [DesainGrafisController::class, 'export']);

    // CRUD
    Route::get('/edit_desain_grafis/{id}', [DesainGrafisController::class, 'edit']);
    Route::put('/update-desain_grafis/{id}', [DesainGrafisController::class, 'update']);
    Route::get('/hapus_desain_grafis/{id}', [DesainGrafisController::class, 'delete']);
    Route::delete('/destroy-desain_grafis/{id}', [DesainGrafisController::class, 'destroy']);

    // Soft delete & restore
    Route::get('/data_desain_grafis/terhapus', [DesainGrafisController::class, 'deletedDesainGrafis']);
    Route::get('/restore-desain_grafis/{id}', [DesainGrafisController::class, 'restoreData']);
    Route::get('/hapus_permanen_desain_grafis/{id}', [DesainGrafisController::class, 'deletePermanen']);
    Route::delete('/force-delete-desain_grafis/{id}', [DesainGrafisController::class, 'forceDelete']);
});
