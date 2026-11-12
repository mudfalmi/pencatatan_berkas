<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\Petugas\SuratController as PetugasSuratController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama diarahkan ke halaman login.
// Bila user sudah login, middleware "guest" akan meneruskannya ke
// dashboard sesuai hak akses (lihat bootstrap/app.php redirectUsersTo).
// Route::redirect dipakai agar route dapat di-cache (bukan Closure).
Route::redirect('/', '/login');

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.attempt');
});

Route::post('/logout', [LoginController::class, 'destroy'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Panel Admin (dilindungi login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/dashboard')->name('home');
    Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    /*
     * Modul khusus Admin (middleware "admin"):
     * - Kelola Surat & Arsip Digital
     * - Kelola Pengguna & Hak Akses
     */
    Route::middleware('admin')->group(function () {
        // Kelola Surat & Arsip Digital
        Route::resource('surat', SuratController::class)->except('show');
        Route::get('surat/{surat}/file', [SuratController::class, 'file'])->name('surat.file');
        Route::get('surat/{surat}/download', [SuratController::class, 'download'])->name('surat.download');

        // Kelola Pengguna & Hak Akses
        Route::resource('users', UserController::class)->except('show');
    });
});

/*
|--------------------------------------------------------------------------
| Panel Petugas (PD Pontren, dilindungi login + middleware "petugas")
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::redirect('/', '/petugas/dashboard')->name('home');
    Route::get('dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');

    /*
     * Modul Surat Masuk & Arsip Digital:
     * Petugas menginput, mencari, mengubah, serta melihat/unduh arsip.
     * Penghapusan data tetap menjadi wewenang Admin.
     */
    Route::middleware('petugas')->group(function () {
        Route::resource('surat', PetugasSuratController::class)->except(['show', 'destroy']);
        Route::get('surat/{surat}/file', [PetugasSuratController::class, 'file'])->name('surat.file');
        Route::get('surat/{surat}/download', [PetugasSuratController::class, 'download'])->name('surat.download');
    });
});
