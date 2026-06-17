<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminMitraController;
use App\Http\Controllers\AdminProdukController;
use App\Http\Controllers\AdminTransaksiController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\MitraDashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'index'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'doLogin'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return auth()->user()->role === 'mitra'
            ? redirect()->route('mitra.dashboard')
            : redirect()->route('admin.dashboard');
    })->name('home');

    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->middleware('role:admin,asisten')
            ->name('admin.dashboard');

        Route::get('/dashboard/mitra', [MitraDashboardController::class, 'index'])
            ->middleware('role:mitra')
            ->name('mitra.dashboard');

        Route::resource('user', AdminUserController::class)
            ->except(['show'])
            ->middleware('role:admin');

        Route::resource('mitra', AdminMitraController::class)
            ->except(['show'])
            ->middleware('role:admin,asisten');

        Route::middleware('role:admin,asisten')->group(function () {
            Route::get('/produk/detail', [AdminProdukController::class, 'detail'])->name('produk.detail');
            Route::get('/kelola-stok', [AdminProdukController::class, 'showKelolaStok'])->name('admin.kelola-stok');
            Route::resource('produk', AdminProdukController::class)->except(['show']);

            Route::get('/transaksi/laporan', [AdminTransaksiController::class, 'laporan'])->name('transaksi.laporan');
            Route::get('/transaksi/print-laporan', [AdminTransaksiController::class, 'cetakLaporan'])->name('transaksi.print');
            Route::put('/transaksi/{transaksi}/status', [AdminTransaksiController::class, 'updateStatus'])->name('transaksi.updateStatus');
        });

        Route::get('/produk/search', [AdminProdukController::class, 'search'])
            ->middleware('role:mitra')
            ->name('produk.search');

        Route::get('/transaksi', [AdminTransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/transaksi/create', [AdminTransaksiController::class, 'create'])->name('transaksi.create');
        Route::get('/transaksi/{transaksi}/edit', [AdminTransaksiController::class, 'edit'])->name('transaksi.edit');
        Route::delete('/transaksi/{transaksi}', [AdminTransaksiController::class, 'destroy'])->name('transaksi.destroy');
        Route::post('/transaksi/{transaksi}/items', [AdminTransaksiController::class, 'addItem'])->name('transaksi.addItem');
        Route::delete('/transaksi/{transaksi}/items/{produk}', [AdminTransaksiController::class, 'removeItem'])->name('transaksi.removeItem');
        Route::get('/transaksi/{transaksi}/bayar', [AdminTransaksiController::class, 'showPaymentPage'])->name('transaksi.showPaymentPage');
        Route::post('/transaksi/{transaksi}/bayar', [AdminTransaksiController::class, 'processPayment'])->name('transaksi.processPayment');
        Route::get('/transaksi/{transaksi}/bukti-pembayaran', [AdminTransaksiController::class, 'paymentProof'])->name('transaksi.paymentProof');
        Route::get('/transaksi/{transaksi}/struk', [AdminTransaksiController::class, 'cetakStruk'])->name('transaksi.struk');
    });
});
