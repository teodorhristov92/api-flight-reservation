<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = [
        'flight_id',
        'seat_number',
        'class',
        'is_booked'
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function booking()
    {
        return $this->hasOne(Booking::class);
    }
}
