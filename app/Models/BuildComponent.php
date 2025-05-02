<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuildComponent extends Model
{
    protected $fillable = ['build_id', 'produk_id', 'component_type', 'quantity', 'subtotal'];

    public function build(): BelongsTo
    {
        return $this->belongsTo(Build::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
}
