<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Socket extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
        'brand_id',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function build()
    {
        return $this->hasMany(Build::class, 'socket_id');
    }
}
