<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'pesanan_id',
        'kode_pembayaran',
        'metode',
        'gateway_id',
        'jumlah',
        'status',
        'waktu_dibayar',
        'url_checkout',
        'response_data',
        'expired_at',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'response_data' => 'json',
        'waktu_dibayar' => 'datetime',
        'expired_at' => 'datetime',
    ];

    // Relasi dengan pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}
