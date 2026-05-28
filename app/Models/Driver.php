<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Vehicle;

class Driver extends Model
{
    protected $fillable = [
        'user_id', 
        'license_number', 
        'phone_number', 
        'status',
        'available',
        'profile_photo',
        'average_rating',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function vehicle(){
    return $this->hasOne(Vehicle::class);
    }
}
