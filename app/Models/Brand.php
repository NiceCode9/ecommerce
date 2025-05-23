<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'is_processor',
        'logo',
    ];

    // Relasi dengan produk
    public function produk()
    {
        return $this->hasMany(Produk::class, 'brand_id');
    }

    public function build()
    {
        return $this->hasMany(Build::class, 'brand_id');
    }
}
