<?php

use App\Http\Controllers\KaryawanController;
use Illuminate\Support\Facades\Route;

// ==================== KARYAWAN ====================
Route::middleware(['auth', 'verified', 'blocked'])->group(function () {
    // Data karyawan
    Route::get('/karyawan', [KaryawanController::class, 'index']);

    // CRUD operations
    Route::get('/karyawan/tambah', [KaryawanController::class, 'create']);
    Route::post('/tambah-karyawan', [KaryawanController::class, 'store']);
    Route::get('/karyawan/edit/{id}', [KaryawanController::class, 'edit']);
    Route::put('/edit-karyawan/{id}', [KaryawanController::class, 'update']);

    // QR Code generation
    Route::get('/karyawan/qr-code/{id}', [KaryawanController::class, 'qrCode']);

    // Delete operations
    Route::get('/karyawan/hapus/{id}', [KaryawanController::class, 'delete']);
    Route::delete('/hapus-karyawan/{id}', [KaryawanController::class, 'destroy']);
});
