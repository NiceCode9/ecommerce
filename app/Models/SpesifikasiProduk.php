<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpesifikasiProduk extends Model
{
    protected $table = 'spesifikasi_produk';
    protected $fillable = [
        'produk_id',
        'nama',
        'nilai',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
