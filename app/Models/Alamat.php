<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $table = "alamat";
    protected $fillable = [
        'pengguna_id',
        'api_id',
        'label',
        'nama_penerima',
        'nomor_telepon',
        'alamat_lengkap',
        'provinsi',
        'kota',
        'kecamatan',
        'kelurahan',
        'kode_pos',
        'catatan',
        'is_utama',
    ];

    protected $cast = [
        'is_utama' => 'boolean'
    ];

    // Relasi dengan pengguna
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    // Relasi dengan pesanan
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'alamat_pengiriman_id');
    }

    // Pengguna yang menggunakan alamat ini sebagai alamat utama
    public function penggunaUtama()
    {
        return $this->hasMany(User::class, 'alamat_utama');
    }
}
