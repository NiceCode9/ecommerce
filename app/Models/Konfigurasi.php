<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konfigurasi extends Model
{
    protected $table = 'konfigurasi';

    protected $fillable = [
        'kunci',
        'nilai',
        'deskripsi',
    ];

    // Method untuk mendapatkan nilai konfigurasi berdasarkan kunci
    public static function getNilai($kunci, $default = null)
    {
        $config = self::where('kunci', $kunci)->first();
        return $config ? $config->nilai : $default;
    }

    // Method untuk menyimpan atau memperbarui konfigurasi
    public static function setNilai($kunci, $nilai, $deskripsi = null)
    {
        return self::updateOrCreate(
            ['kunci' => $kunci],
            [
                'nilai' => $nilai,
                'deskripsi' => $deskripsi,
            ]
        );
    }
}
