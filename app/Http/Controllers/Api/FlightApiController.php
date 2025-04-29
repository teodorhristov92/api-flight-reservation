<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use App\Models\Seat;

class FlightApiController extends Controller
{
    public function cancel($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking ID not found.'
            ], 404);
        }
        $booking->delete();
        
        return response()->json(['message' => 'Booking cancelled']);
    }

    public function report($flightId)
    {
        $flight = Flight::find($flightId);

        if (!$flight) {
            return response()->json([
                'success' => false,
                'message' => 'Flight ID not found.'
            ], 404);
        }

        $booked = $flight->bookings()->count();
        $loadFactor = round(($booked / $flight->capacity) * 100, 2);

        return response()->json([
            'flight' => $flight->code,
            'capacity' => $flight->capacity,
            'booked' => $booked,
            'load_factor' => "$loadFactor%"
        ]);
    }
    
    public function reportReservedSeats($flightId) {
        $flight = Flight::find($flightId);
        
        if (!$flight) {
            return response()->json([
                'success' => false,
                'message' => 'Flight ID not found.'
            ], 404);
        }

        $reservedSeats = Seat::where('flight_id', $flightId)
                                ->where('is_booked', true)
                                ->pluck('seat_number')
                                ->toArray();

        $booked = $flight->bookings()->count();
        
        return response()->json([
            'flight' => $flight->code,
            'capacity' => $flight->capacity,
            'booked' => $booked,
            'reservedSeats' => $reservedSeats
        ]);
    }
    
}