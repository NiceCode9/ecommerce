<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'gambar',
        'parent_id',
        'tipe',
    ];

    // Relasi dengan kategori induk
    public function parent()
    {
        return $this->belongsTo(Kategori::class, 'parent_id');
    }

    // Relasi dengan sub-kategori
    public function children()
    {
        return $this->hasMany(Kategori::class, 'parent_id');
    }

    // Relasi dengan produk
    public function produk()
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }
}
