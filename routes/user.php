<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'blocked'])->group(function () {
    Route::get('/user', [UserController::class, 'index']);
    Route::get('/user/create', [UserController::class, 'create']);
    Route::post('/user/store', [UserController::class, 'store']);
    Route::get('/user/{id}/edit', [UserController::class, 'edit']);
    Route::put('/user/{id}/update', [UserController::class, 'update']);
    Route::get('/user/{id}/delete', [UserController::class, 'delete']);
    Route::delete('/user/{id}/destroy', [UserController::class, 'destroy']);
    Route::post('/user/{id}/toggle-block', [UserController::class, 'toggleBlock']);
});
