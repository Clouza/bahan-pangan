<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\BahanPanganController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CommodityController;

// Route for guest
Route::get('/', function () {
    return view('landing');
})->name('welcome');

// Authentication Routes
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('bahan-pangan', BahanPanganController::class);
        Route::resource('users', UserController::class);
        Route::resource('commodities', CommodityController::class);
        Route::get('bahan-pangan/visualization', [DashboardController::class, 'showVisualization'])->name('bahan-pangan.visualization');
    });

    // data routes (for transaksi and history pembayaran)
    Route::prefix('data')->name('data.')->group(function () {
        Route::get('transaksi', function () {
            return view('data.transaksi');
        })->name('transaksi');

        Route::get('history-pembayaran', function () {
            return view('data.history-pembayaran');
        })->name('history-pembayaran');
    });
});