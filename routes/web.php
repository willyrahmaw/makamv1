<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MakamController as AdminMakamController;
use App\Http\Controllers\Admin\BlokMakamController as AdminBlokController;
use App\Http\Controllers\Admin\KeuanganController as AdminKeuanganController;

/*
|--------------------------------------------------------------------------
| Public Routes (Untuk Pengunjung)
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/cari', [PublicController::class, 'search'])->name('search');
Route::get('/makam/{makam}', [PublicController::class, 'show'])->name('detail');
Route::get('/denah', [PublicController::class, 'denah'])->name('denah');
Route::get('/blok/{blok}', [PublicController::class, 'showBlok'])->name('blok.show');
Route::get('/peta', [PublicController::class, 'peta'])->name('peta');
Route::get('/laporan-keuangan', [PublicController::class, 'keuangan'])->name('keuangan.public');
Route::get('/laporan-keuangan/export', [PublicController::class, 'exportKeuangan'])->name('keuangan.public.export');

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

/*
|--------------------------------------------------------------------------
| Admin Protected Routes (Membutuhkan Login)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware('admin')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Data Makam
    Route::resource('makam', AdminMakamController::class)->names([
        'index' => 'admin.makam.index',
        'create' => 'admin.makam.create',
        'store' => 'admin.makam.store',
        'show' => 'admin.makam.show',
        'edit' => 'admin.makam.edit',
        'update' => 'admin.makam.update',
        'destroy' => 'admin.makam.destroy',
    ]);

    // Blok Makam
    Route::resource('blok', AdminBlokController::class)->except(['show'])->names([
        'index' => 'admin.blok.index',
        'create' => 'admin.blok.create',
        'store' => 'admin.blok.store',
        'edit' => 'admin.blok.edit',
        'update' => 'admin.blok.update',
        'destroy' => 'admin.blok.destroy',
    ]);

    // Keuangan / Donasi
    Route::resource('keuangan', AdminKeuanganController::class)->except(['show'])->names([
        'index' => 'admin.keuangan.index',
        'create' => 'admin.keuangan.create',
        'store' => 'admin.keuangan.store',
        'edit' => 'admin.keuangan.edit',
        'update' => 'admin.keuangan.update',
        'destroy' => 'admin.keuangan.destroy',
    ]);
    Route::get('keuangan/export', [AdminKeuanganController::class, 'export'])->name('admin.keuangan.export');

    // Sejarah
    Route::resource('sejarah', \App\Http\Controllers\Admin\SejarahController::class)->names([
        'index' => 'admin.sejarah.index',
        'create' => 'admin.sejarah.create',
        'store' => 'admin.sejarah.store',
        'edit' => 'admin.sejarah.edit',
        'update' => 'admin.sejarah.update',
        'destroy' => 'admin.sejarah.destroy',
    ]);

    // Kontak Admin
    Route::get('kontak', [\App\Http\Controllers\Admin\KontakAdminController::class, 'edit'])->name('admin.kontak.edit');
    Route::put('kontak', [\App\Http\Controllers\Admin\KontakAdminController::class, 'update'])->name('admin.kontak.update');

    // Settings
    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'edit'])->name('admin.settings.edit');
    Route::put('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('admin.settings.update');
});
