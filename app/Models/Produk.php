<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $fillable = [
        'nama',
        'slug',
        'sku',
        'deskripsi',
        'harga',
        'harga_setelah_diskon',
        'diskon',
        'stok',
        'berat',
        'kondisi',
        'kategori_id',
        'brand_id',
        'socket_id',
        'mobo_id',
        'garansi_bulan',
        'is_aktif',
        'dilihat',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'diskon' => 'decimal:2',
        'berat' => 'decimal:2',
        'is_aktif' => 'boolean',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function socket()
    {
        return $this->belongsTo(Socket::class);
    }

    // Relasi ke motherboard (produk yang direferensikan)
    public function motherboard()
    {
        return $this->belongsTo(Produk::class, 'mobo_id');
    }

    // Relasi ke produk-produk yang kompatibel dengan motherboard ini
    public function kompatibel_dengan_motherboard_ini()
    {
        return $this->hasMany(Produk::class, 'mobo_id');
    }

    public function gambar()
    {
        return $this->hasMany(GambarProduk::class, 'produk_id');
    }

    public function spesifikasi()
    {
        return $this->hasMany(SpesifikasiProduk::class);
    }

    // Relasi dengan gambar utama
    public function gambarUtama()
    {
        return $this->hasOne(GambarProduk::class, 'produk_id')->where('is_utama', true);
    }

    // Relasi dengan keranjang
    public function carts()
    {
        return $this->hasMany(Keranjang::class, 'produk_id');
    }

    // Relasi dengan wishlist
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class, 'produk_id');
    }

    // Relasi dengan detail pesanan
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'produk_id');
    }

    // Relasi dengan ulasan
    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'produk_id');
    }

    // Menghitung harga setelah diskon
    // public function getHargaSetelahDiskonAttribute()
    // {
    //     if ($this->diskon > 0) {
    //         return $this->harga - ($this->harga * $this->diskon / 100);
    //     }
    //     return $this->harga;
    // }

}
