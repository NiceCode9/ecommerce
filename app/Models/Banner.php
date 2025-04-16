<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banner';

    protected $fillable = [
        'judul',
        'deskripsi',
        'gambar',
        'url',
        'urutan',
        'is_aktif',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Scope untuk banner aktif
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true)
            ->where(function ($q) {
                $q->whereNull('tanggal_mulai')
                    ->orWhere('tanggal_mulai', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                    ->orWhere('tanggal_selesai', '>=', now());
            })
            ->orderBy('urutan');
    }
}
