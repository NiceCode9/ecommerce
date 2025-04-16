<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatStatusPesanan extends Model
{
    protected $table = 'riwayat_status_pesanan';
    public $timestamps = false;

    protected $fillable = [
        'pesanan_id',
        'status',
        'catatan',
    ];

    // Relasi dengan pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}
