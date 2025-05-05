<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\BerasController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PemilikController;
use App\Http\Controllers\ProdusenController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/Register', [AuthenticatedController::class, 'registerPage'])->name('register.page');

    Route::post('/Register', [AuthenticatedController::class, 'submitRegister'])->name('register.submit');

    Route::post('login', [AuthenticatedController::class, 'submitLogin'])->name('login.submit');

});

Route::middleware('auth')->group(function () {
    Route::get('logout', [AuthenticatedController::class, 'logout'])->name('logout');
});

// admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'dashboardPage'])->name('admin.dashboard');

    // Route Produsen
    Route::get('admin/produsen', [ProdusenController::class, 'index'])->name('admin.produsen.index');

    // Route::post('admin/produsen/tambah', [ProdusenController::class, 'store'])->name('admin.produsen.store');
    // Route::post('admin/produsen/update', [ProdusenController::class, 'update'])->name('admin.produsen.update');
    // Route::post('admin/produsen/destroy', [ProdusenController::class, 'destroy'])->name('admin.produsen.destroy');


    // Route Beras
    Route::get('admin/beras', [BerasController::class, 'index'])->name('admin.beras.index');

    // Route::post('admin/beras/tambah', [BerasController::class, 'store'])->name('admin.beras.store');
    // Route::post('admin/beras/update', [BerasController::class, 'update'])->name('admin.beras.update');
    // Route::post('admin/beras/destroy', [BerasController::class, 'destroy'])->name('admin.beras.destroy');

    // Route Gudang
    Route::get('admin/gudang', [GudangController::class, 'index'])->name('admin.gudang.index');
    Route::post('admin/gudang/store', [GudangController::class, 'store'])->name('admin.gudang.store');
    Route::post('admin/gudang/update', [GudangController::class, 'update'])->name('admin.gudang.update');
    Route::post('admin/gudang/destroy', [GudangController::class, 'destroy'])->name('admin.gudang.destroy');

    // Route Pemesanan
    Route::get('admin/pemesanan', [PemesananController::class, 'index'])->name('admin.pemesanan.index');
    Route::post('admin/pemesanan/store', [PemesananController::class, 'store'])->name('admin.pemesanan.store');
    Route::post('admin/pemesanan/update', [PemesananController::class, 'update'])->name('admin.pemesanan.update');
    Route::post('admin/pemesanan/destroy', [PemesananController::class, 'destroy'])->name('admin.pemesanan.update');

    // Route::resource('admin/pemesanan', PemesananController::class)->names([
    //     'index'   => 'admin.pemesanan.index',
    //     'store'   => 'admin.pemesanan.store',
    //     'update'  => 'admin.pemesanan.update',
    //     'destroy' => 'admin.pemesanan.destroy',
    // ]);

    // Route Pemesanan
    // Route::resource('admin/gudang', GudangController::class)->names([
    //     'index'   => 'admin.gudang.index',
    //     'store'   => 'admin.gudang.store',
    //     'update'  => 'admin.gudang.update',
    //     'destroy' => 'admin.gudang.destroy',
    // ]);
});

Route::middleware(['auth', 'pemilik'])->group(function () {
    Route::get('pemilik/dashboard', [PemilikController::class, 'dashboardPage'])->name('pemilik.dashboard');

    Route::get('pemilik/produsen', [PemilikController::class, 'produsenPage'])->name('pemilik.produsen.index');

    Route::post('pemilik/produsen', [PemilikController::class, 'validasiProdusen'])->name('pemilik.produsen.validasi');
});

Route::middleware(['auth', 'produsen'])->group(function () {
    Route::get('/produsen/dashboard', [ProdusenController::class, 'dashboardPage'])->name('produsen.dashboard');

    Route::get('/produsen/beras', [BerasController::class, 'index'])->name('produsen.beras.index');

    Route::post('produsen/beras/tambah', [BerasController::class, 'store'])->name('produsen.beras.store');
    Route::post('produsen/beras/update', [BerasController::class, 'update'])->name('produsen.beras.update');
    Route::post('produsen/beras/destroy', [BerasController::class, 'destroy'])->name('produsen.beras.destroy');
});

