<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillabel = [
        'user_id', 
        'license_number', 
        'phone_number', 
        'status'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
