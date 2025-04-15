<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedController::class, 'submitLogin'])->name('login.submit');

});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedController::class, 'destroy'])
        ->name('logout');
});

// admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'dashboardPage'])->name('admin.dashboard');
});
