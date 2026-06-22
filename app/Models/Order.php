<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'pickup',
        'destination',
        'pickup_lat',
        'pickup_lng',
        'destination_lat',
        'destination_lng',
        'distance',
        'vehicle_type',
        'price',
        'status',
    ];

    protected $casts = [
        'pickup_lat'      => 'float',
        'pickup_lng'      => 'float',
        'destination_lat' => 'float',
        'destination_lng' => 'float',
        'distance'        => 'float',
        'price'           => 'integer',
    ];
}