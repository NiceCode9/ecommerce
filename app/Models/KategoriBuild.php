<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBuild extends Model
{
    protected $fillable = ['nama', 'slug'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function build()
    {
        return $this->hasMany(Build::class, 'kategori_id');
    }
}
