<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Build extends Model
{
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function components(): HasMany
    {
        return $this->hasMany(BuildComponent::class);
    }
}
