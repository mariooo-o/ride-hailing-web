<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Driver;
use App\Models\Rating;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'driver_id',
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

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function driver(){
        return $this->belongsTo(Driver::class);
    }

    public function payment(){
    return $this->hasOne(Payment::class);
    }

    public function ratings(){
        return $this->hasMany(Rating::class);
    }
}