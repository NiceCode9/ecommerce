<?php

use App\Http\Controllers\AlamatController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\Front\CheckOutController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\WishlistController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\Front\PesananController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SettingController;
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
            Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
            // Route::get('/pesanan/{id}', [PesananController::class, 'show'])->name('pesanan.show');
        });
        Route::prefix('alamat')->group(function () {
            Route::get('cities/{provinceId}', [AlamatController::class, 'getCities'])->name('alamat.cities');
            Route::get('/get-alamat-pelanggan', [AlamatController::class, 'getAlamat'])->name('alamat.get-alamat-pelanggan');
            Route::post('store', [AlamatController::class, 'store'])->name('alamat.store');
        });

        Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/{id}', [PesananController::class, 'show'])->name('pesanan.show');
        Route::post('/pesanan/{id}/confirm-cod', [PesananController::class, 'confirmCodPayment'])
            ->name('pesanan.confirm-cod');
        Route::get('/payment/success', [SettingController::class, 'success'])->name('payment.success');
        Route::get('/payment/failed', [SettingController::class, 'failed'])->name('payment.failed');
    });
