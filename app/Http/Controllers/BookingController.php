<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    //user_id
    //flight_id
    //seat_number
    
    public function index()
    {
        return Booking::with(['user', 'flight'])->get();
    }

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

    public function show(Booking $booking)
    {
        return $booking->load(['user', 'flight']);
    }

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

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->json(null, 204);
    }
}
