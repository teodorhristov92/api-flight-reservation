<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

class FlightApiController extends Controller
{
    public function book(Request $request)
    {
        $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'user_id' => 'required|string|max:255',
            'seat_number' => 'required'
        ]);
        $flight = Flight::find($request->flight_id);

        $max_capacity_flight = Flight::find($request->flight_id);

        if ($request->seat_number > $max_capacity_flight->capacity) {
            return response()->json([
                'error' => 'Seat number exceeds flight capacity!'
            ], 400);
        }
        
        // Check if the seat is already booked
        $seatTaken = $flight->bookings()->where('seat_number', $request->seat_number)->exists();
        if ($seatTaken) {
            return response()->json([
                'error' => 'Seat number is already booked!'
            ], 400);
        }

        if ($flight->bookings()->count() >= $flight->capacity) {
            return response()->json([
                'error' => 'Flight is full'
            ], 400);
        }
        
        $booking = $flight->bookings()->create([
            'user_id' => $request->user_id,
            'seat_number' => $request->seat_number
        ]);

        return response()->json(['message' => 'Seat booked', 'booking' => $booking]);
    }

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
}