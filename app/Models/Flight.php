<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = ['origin', 'destination', 'datetime', 'capacity'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
