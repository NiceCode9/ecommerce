<?php

use App\Http\Controllers\KategoriController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'checkrole:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('kategori', KategoriController::class)->except(['show']);
});
