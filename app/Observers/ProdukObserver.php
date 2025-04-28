<?php

namespace App\Observers;

use App\Models\Produk;

class ProdukObserver
{
    public function created(Produk $produk)
    {
        $produk->harga_setelah_diskon = $produk->diskon > 0
            ? $produk->harga - ($produk->harga * $produk->diskon / 100)
            : $produk->harga;
    }

    public function updated(Produk $produk)
    {
        $produk->harga_setelah_diskon = $produk->diskon > 0
            ? $produk->harga - ($produk->harga * $produk->diskon / 100)
            : $produk->harga;
    }
}
