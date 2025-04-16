<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = "keranjang";
    protected $fillable = [
        'pengguna_id',
        'produk_id',
        'jumlah',
        'catatan',
    ];

    // Relasi dengan pengguna
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    // Relasi dengan produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    // Menghitung subtotal
    public function getSubtotalAttribute()
    {
        return $this->produk->harga_setelah_diskon * $this->jumlah;
    }
}
