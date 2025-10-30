<?php

use App\Http\Controllers\PengeluaranController;
use Illuminate\Support\Facades\Route;

// ==================== PENGELUARAN ====================
Route::middleware(['auth', 'verified', 'blocked'])->group(function () {
    Route::get('/pengeluaran', [PengeluaranController::class, 'index']);
    Route::get('/pengeluaran/tambah', [PengeluaranController::class, 'create']);
    Route::post('/tambah-pengeluaran', [PengeluaranController::class, 'store']);
    Route::get('/pengeluaran/edit/{id}', [PengeluaranController::class, 'edit']);
    Route::put('/edit-pengeluaran/{id}', [PengeluaranController::class, 'update']);
    Route::get('/pengeluaran/hapus/{id}', [PengeluaranController::class, 'delete']);
    Route::delete('/destroy-pengeluaran/{id}', [PengeluaranController::class, 'destroy']);
    Route::get('/pengeluaran/restore', [PengeluaranController::class, 'deletedPengeluaran']);
    Route::get('/restore-pengeluaran/{id}', [PengeluaranController::class, 'restoreData']);
    Route::get('/pengeluaran/hapus_permanen/{id}', [PengeluaranController::class, 'deletePermanen']);
    Route::delete('/force_delete-pengeluaran/{id}', [PengeluaranController::class, 'forceDelete']);
    Route::post('/pengeluaran/export', [PengeluaranController::class, 'export']);
});
