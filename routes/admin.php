<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SocketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'checkrole:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('kategori', KategoriController::class)->except(['show']);
    Route::resource('brand', BrandController::class)->except(['show']);
    Route::resource('socket', SocketController::class)->except(['show']);
    Route::resource('produk', ProdukController::class);
});
