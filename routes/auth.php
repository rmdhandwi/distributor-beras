<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\BerasController;
use App\Http\Controllers\ProdusenController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
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

    Route::post('admin/produsen/tambah', [ProdusenController::class, 'store'])->name('admin.produsen.store');
    Route::post('admin/produsen/update', [ProdusenController::class, 'update'])->name('admin.produsen.update');

    // Route Beras
    Route::get('admin/beras', [BerasController::class, 'index'])->name('admin.beras.index');

    Route::post('admin/beras/tambah', [BerasController::class, 'store'])->name('admin.beras.store');
    Route::post('admin/beras/update', [BerasController::class, 'update'])->name('admin.beras.update');
});
