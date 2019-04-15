<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    protected $fillable = [
        'name', 'duration', 'price',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'tariffs_locations');
    }
}
