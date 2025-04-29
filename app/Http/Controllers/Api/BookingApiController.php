<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Seat;
use App\Models\Flight;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\SeatController;

class BookingApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Booking::with(['user', 'flight'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'flight_id' => 'required|exists:flights,id',
            'seat_number' => 'required|string|max:10',
        ]);

        $booking = Booking::create($request->all());
        return response()->json($booking, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        return $booking->load(['user', 'flight']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'flight_id' => 'sometimes|exists:flights,id',
            'seat_number' => 'sometimes|string|max:10',
        ]);

        $booking->update($request->all());
        return response()->json($booking);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function book(Request $request)
    {
        $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'user_id' => 'required|max:255',
            'seat_number' => 'required',
            'seat_type' => 'required|string|max:255'
        ]);
        
        $flight = Flight::find($request->flight_id);
        
        $max_capacity_flight = Flight::find($request->flight_id);
       
        if ($request->seat_number > $max_capacity_flight->capacity) {
            return response()->json([
                'error' => 'Seat number exceeds flight capacity!'
            ], 400);
        }
        
        $seat = Seat::where('flight_id', $request->flight_id)
        ->where('seat_number', $request->seat_number)
        ->first();
        
        
        if (!$seat) {
            $seat = Seat::create([
                'seat_number' => $request->seat_number,
                'class' => $request->seat_type,
                'flight_id' => $request->flight_id,
                'is_booked' => false
            ]);
        }

        // If seat is already booked, return error
        if ($seat->is_booked) {
            return response()->json([
                'error' => 'Seat number is already booked!'
            ], 400);
        }

        //Check if flight is already full
        if ($flight->bookings()->count() >= $flight->capacity) {
            return response()->json([
                'error' => 'Flight is full'
            ], 400);
        }

        // Mark the seat as booked
        $seat->update(['is_booked' => true]);

        // Create the booking
        $booking = $flight->bookings()->create([
            'user_id' => $request->user_id,
            'seat_number' => $request->seat_number
        ]);

        return response()->json([
            'message' => 'Seat booked successfully',
            'booking' => $booking
        ]);
    }
}
