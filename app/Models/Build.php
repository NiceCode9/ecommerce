<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Build extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'user_id',
        'kategori_id',
        'name',
        'slug',
        'description',
        'total_price',
        'mode',
        'status',
        'is_public',
        'brand_id',
        'socket_id',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    public function components()
    {
        return $this->hasMany(BuildComponent::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriBuild::class, 'kategori_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function socket()
    {
        return $this->belongsTo(Socket::class, 'socket_id');
    }
}
