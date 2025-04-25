<?php

use App\Http\Controllers\Front\ProdukFrontController;
use App\Http\Controllers\Front\UlasanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products/quick-view/{id}', [ProdukController::class, 'quickView'])->name('products.quick-view');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/get-wilayah', [SettingController::class, 'getWilayah'])->name('api.getwilayah');
    Route::get('/calculate-cost', [SettingController::class, 'calculateShipping'])->name('api.calculate-shipping');


    Route::post('/produk/{produkId}/ulasan', [UlasanController::class, 'store'])->name('produk.ulasan.store');
    Route::post('/produk/ulasan/{ulasanId}/reply', [UlasanController::class, 'reply'])->name('produk.ulasan.reply');
});

Route::get('/produk', [ProdukFrontController::class, 'index'])->name('produk.index');
Route::get('/produk/{id}', [ProdukFrontController::class, 'show'])->name('produk.detail');
Route::get('/produk/kategori/{id}', [ProdukFrontController::class, 'byCategory'])->name('produk.kategori');
Route::get('/produk/brand/{id}', [ProdukFrontController::class, 'byBrand'])->name('produk.brand');

Route::post('midtrans/callback', [App\Http\Controllers\MidtransController::class, 'handleNotification'])->name('midtrans.callback');

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/customer.php';
