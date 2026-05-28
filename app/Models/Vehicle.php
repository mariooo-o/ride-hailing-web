<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Driver;

class Vehicle extends Model
{
        protected $fillable = [
        'driver_id',
        'brand',
        'model',
        'color',
        'plate_number',
        'year',
        'type',
        'verification_status',
    ];

    public function driver(){
        return $this->belongsTo(Driver::class);
    }
}
