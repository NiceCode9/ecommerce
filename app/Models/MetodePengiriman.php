<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodePengiriman extends Model
{
    protected $table = 'metode_pengiriman';

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    // Relasi dengan pesanan
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'metode_pengiriman_id');
    }
}
