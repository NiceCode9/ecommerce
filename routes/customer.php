<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SocketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'checkrole:pelanggan'])
    ->name('pelanggan.')
    ->group(function () {});
