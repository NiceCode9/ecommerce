<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ManagePesananController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SocketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'checkrole:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('kategori', KategoriController::class)->except(['show']);
    Route::resource('brand', BrandController::class)->except(['show']);
    Route::resource('socket', SocketController::class)->except(['show']);
    Route::resource('produk', ProdukController::class);

    Route::delete('produk/gambar/{gambar}', [ProdukController::class, 'destroyGambar'])->name('produk.destroyGambar');
    Route::get('/produk/{id}/gambar', [ProdukController::class, 'getGambar'])->name('produk.getgambar');
    Route::post('/produk/gambar/set-utama/{id}', [ProdukController::class, 'setGambarUtama'])->name('produk.gambar.set-utama');
    Route::post('/produk/gambar/upload', [ProdukController::class, 'uploadGambar'])->name('produk.gambar.upload');
    Route::post('/produk/gambar/update-urutan', [ProdukController::class, 'updateUrutanGambar'])->name('produk.gambar.update-urutan');

    Route::get('/pesanan', [ManagePesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{id}', [ManagePesananController::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/{id}/update-status', [ManagePesananController::class, 'updateStatus'])->name('pesanan.update-status');
});


// The DELETE method is not supported for route admin/produk. Supported methods: GET, HEAD, POST.
