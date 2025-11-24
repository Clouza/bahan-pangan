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
    Route::get('/dashboard/export-excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export-excel');
    Route::get('/kurs-dollar', [DashboardController::class, 'kursDollar'])->name('kurs.dollar');
    Route::get('/harga-emas', [DashboardController::class, 'hargaEmas'])->name('harga.emas');

    // Admin Routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('bahan-pangan/export-excel', [BahanPanganController::class, 'exportExcel'])->name('bahan-pangan.export-excel');
        Route::get('bahan-pangan/export-csv', [BahanPanganController::class, 'exportCsv'])->name('bahan-pangan.export-csv');
        Route::post('bahan-pangan/import', [BahanPanganController::class, 'import'])->name('bahan-pangan.import');
        Route::get('bahan-pangan/visualization', [BahanPanganController::class, 'visualization'])->name('bahan-pangan.visualization');
        Route::resource('bahan-pangan', BahanPanganController::class);
        Route::resource('users', UserController::class);
        Route::resource('commodities', CommodityController::class);
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

Route::get('/api/harga-pangan/{commodity}', [DashboardController::class, 'getHargaPanganByCommodity']);
