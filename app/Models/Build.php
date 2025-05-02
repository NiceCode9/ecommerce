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
        'name',
        'slug',
        'description',
        'total_price',
        'mode',
        'status',
        'is_public'
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
}
