<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $fillable = [
        'nomor_pesanan',
        'pengguna_id',
        'status',
        'total_harga',
        'total_diskon',
        'biaya_pengiriman',
        'total_bayar',
        'alamat_pengiriman_id',
        // 'metode_pengiriman_id',
        'metode_pengiriman',
        'nomor_resi',
        'catatan',
        'catatan_admin',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
        'total_diskon' => 'decimal:2',
        'biaya_pengiriman' => 'decimal:2',
        'total_bayar' => 'decimal:2',
    ];

    // Relasi dengan pengguna
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    // Relasi dengan alamat pengiriman
    public function alamatPengiriman()
    {
        return $this->belongsTo(Alamat::class, 'alamat_pengiriman_id');
    }

    // Relasi dengan metode pengiriman
    public function metodePengiriman()
    {
        return $this->belongsTo(MetodePengiriman::class, 'metode_pengiriman_id');
    }

    // Relasi dengan detail pesanan
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }

    // Relasi dengan pembayaran
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pesanan_id');
    }

    // Relasi dengan riwayat status
    public function riwayatStatus()
    {
        return $this->hasMany(RiwayatStatusPesanan::class, 'pesanan_id')->orderBy('created_at', 'desc');
    }

    // Relasi dengan ulasan
    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'pesanan_id');
    }

    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case 'menunggu_pembayaran':
                return 'Menunggu Pembayaran';
            case 'diproses':
                return 'Diproses';
            case 'dikirim':
                return 'Dikirim';
            case 'selesai':
                return 'Selesai';
            case 'dibatalkan':
                return 'Dibatalkan';
            default:
                return 'Status Tidak Dikenal';
        }
    }
    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'menunggu_pembayaran':
                return 'warning';
            case 'diproses':
                return 'info';
            case 'dikirim':
                return 'primary';
            case 'selesai':
                return 'success';
            case 'dibatalkan':
                return 'danger';
            default:
                return 'secondary';
        }
    }
}
