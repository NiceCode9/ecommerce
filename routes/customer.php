<?php

use App\Http\Controllers\AlamatController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\WishlistController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SocketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'checkrole:pelanggan'])
    ->name('pelanggan.')
    ->group(function () {
        Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
        Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

        // wishlist
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::get('/wishlist/ajax', [WishlistController::class, 'ajaxIndex'])->name('wishlist.ajax');
        Route::delete('/wishlist/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
        Route::get('/wishlist/sort', [WishlistController::class, 'sort'])->name('wishlist.sort');

        // checkout
        Route::prefix('checkout')->group(function () {
            Route::get('/', [CheckOutController::class, 'index'])->name('checkout.index');
            Route::post('/calculate-shipping', [CheckOutController::class, 'calculateShipping'])->name('checkout.calculate-shipping');
            Route::post('/process', [CheckOutController::class, 'process'])->name('checkout.process');
        });
        Route::prefix('alamat')->group(function() {
            Route::get('provinces', [AlamatController::class, 'getProvinces'])->name('alamat.provinces');
            Route::get('cities/{provinceId}', [AlamatController::class, 'getCities'])->name('alamat.cities');
            Route::post('store', [AlamatController::class, 'store'])->name('alamat.store');
        });
    });
