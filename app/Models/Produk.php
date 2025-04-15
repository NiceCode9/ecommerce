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
        return $this->hasMany(GambarProduk::class);
    }

    public function spesifikasi()
    {
        return $this->hasMany(SpesifikasiProduk::class);
    }
}
