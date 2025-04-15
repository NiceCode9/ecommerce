<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GambarProduk extends Model
{
    protected $table = 'gambar_produk';
    protected $fillable = [
        'produk_id',
        'gambar',
        'is_utama',
        'urutan',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
