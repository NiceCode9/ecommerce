<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'pengguna_id',
        'produk_id',
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
}
