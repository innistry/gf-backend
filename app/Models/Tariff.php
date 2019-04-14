<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    protected $fillable = [
        'params',
    ];

    protected $casts = [
        'params' => 'collection',
    ];

    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }
}
