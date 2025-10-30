<?php

use App\Http\Controllers\VideoFotoController;
use Illuminate\Support\Facades\Route;

// ==================== VIDEO EDITING & FOTOGRAFI ====================
Route::get('/daftar_video_editing_fotografi', [VideoFotoController::class, 'create']);
Route::post('/tambah-video_editing_fotografi', [VideoFotoController::class, 'store']);

Route::middleware(['auth', 'verified', 'blocked'])->group(function () {
    // Data utama
    Route::get('/data_video_editing_fotografi', [VideoFotoController::class, 'index']);
    Route::get('/data_video_editing_fotografi/filter', [VideoFotoController::class, 'filterData']);
    Route::post('/data_video_editing_fotografi/export', [VideoFotoController::class, 'export']);

    // CRUD
    Route::get('/edit_video_editing_fotografi/{id}', [VideoFotoController::class, 'edit']);
    Route::put('/update-video_editing_fotografi/{id}', [VideoFotoController::class, 'update']);
    Route::get('/hapus_video_editing_fotografi/{id}', [VideoFotoController::class, 'delete']);
    Route::delete('/destroy-video_editing_fotografi/{id}', [VideoFotoController::class, 'destroy']);

    // Soft delete & restore
    Route::get('/data_video_editing_fotografi/terhapus', [VideoFotoController::class, 'deletedVideoFoto']);
    Route::get('/restore-video_editing_fotografi/{id}', [VideoFotoController::class, 'restoreData']);
    Route::get('/hapus_permanen_video_editing_fotografi/{id}', [VideoFotoController::class, 'deletePermanen']);
    Route::delete('/force-delete-video_editing_fotografi/{id}', [VideoFotoController::class, 'forceDelete']);
});
