<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';

    protected $fillable = [
        'pesanan_id',
        'produk_id',
        'nama_produk',
        'harga',
        'diskon',
        'jumlah',
        'subtotal',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'diskon' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relasi dengan pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    // Relasi dengan produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
