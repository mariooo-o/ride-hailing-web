<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['order_id', 'rater_id', 'rated_id', 'stars', 'type', 'comment'];

    public function rater() {
        return $this->belongsTo(User::class, 'rater_id');
    }

    public function rated() {
        return $this->belongsTo(User::class, 'rated_id');
    }
}