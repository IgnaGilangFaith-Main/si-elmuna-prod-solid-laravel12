<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ==================== AUTH & DASHBOARD ====================
Route::get('/', [HomeController::class, 'index']);
Route::get('/dashboard', [HomeController::class, 'adminDashboard'])->middleware(['auth', 'verified', 'blocked']);

Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticating'])->middleware('throttle:login');
Route::get('/logout', [AuthController::class, 'logout'])->middleware(['auth', 'verified', 'blocked']);

// ==================== MODUL KURSUS ====================
require __DIR__.'/bahasa_inggris.php';
require __DIR__.'/desain_grafis.php';
require __DIR__.'/digital_marketing.php';
require __DIR__.'/komputer.php';
require __DIR__.'/mengemudi.php';
require __DIR__.'/public_speaking.php';
require __DIR__.'/pemrograman.php';
require __DIR__.'/video_foto.php';

// ==================== MODUL KEUANGAN ====================
require __DIR__.'/pemasukan.php';
require __DIR__.'/pengeluaran.php';
require __DIR__.'/kuitansi.php';

// ==================== MODUL LAPORAN ====================
Route::middleware(['auth', 'verified', 'blocked'])->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index']);
    Route::post('/laporan/export', [LaporanController::class, 'export']);
});

// ==================== MODUL SERTIFIKAT ====================
require __DIR__.'/sertifikat_bahasa_inggris.php';
require __DIR__.'/sertifikat_komputer.php';
require __DIR__.'/sertifikat_desain_grafis.php';
require __DIR__.'/sertifikat_digital_marketing.php';
require __DIR__.'/sertifikat_mengemudi.php';
require __DIR__.'/sertifikat_pemrograman.php';
require __DIR__.'/sertifikat_public_speaking.php';
require __DIR__.'/sertifikat_video_foto.php';

// ==================== MODUL SDM ====================
require __DIR__.'/karyawan.php';
require __DIR__.'/presensi.php';

// ==================== MODUL USER ====================
require __DIR__.'/user.php';
