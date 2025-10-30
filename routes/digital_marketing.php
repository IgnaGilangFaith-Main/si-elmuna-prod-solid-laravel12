<?php

use App\Http\Controllers\DigitalMarketingController;
use Illuminate\Support\Facades\Route;

// ==================== DIGITAL MARKETING ====================
Route::get('/daftar_digital_marketing', [DigitalMarketingController::class, 'create']);
Route::post('/tambah-digital_marketing', [DigitalMarketingController::class, 'store']);

Route::middleware(['auth', 'verified', 'blocked'])->group(function () {
    // Data utama
    Route::get('/data_digital_marketing', [DigitalMarketingController::class, 'index']);
    Route::get('/data_digital_marketing/filter', [DigitalMarketingController::class, 'filterData']);
    Route::post('/data_digital_marketing/export', [DigitalMarketingController::class, 'export']);

    // CRUD
    Route::get('/edit_digital_marketing/{id}', [DigitalMarketingController::class, 'edit']);
    Route::put('/update-digital_marketing/{id}', [DigitalMarketingController::class, 'update']);
    Route::get('/hapus_digital_marketing/{id}', [DigitalMarketingController::class, 'delete']);
    Route::delete('/destroy-digital_marketing/{id}', [DigitalMarketingController::class, 'destroy']);

    // Soft delete & restore
    Route::get('/data_digital_marketing/terhapus', [DigitalMarketingController::class, 'deletedDigitalMarketing']);
    Route::get('/restore-digital_marketing/{id}', [DigitalMarketingController::class, 'restoreData']);
    Route::get('/hapus_permanen_digital_marketing/{id}', [DigitalMarketingController::class, 'deletePermanen']);
    Route::delete('/force-delete-digital_marketing/{id}', [DigitalMarketingController::class, 'forceDelete']);
});
