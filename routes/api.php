<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FlightApiController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\SeatController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/book', [BookingApiController::class, 'book']);

Route::post('/show', [BookingApiController::class, 'show']);

Route::delete('/cancel/{id}', [FlightApiController::class, 'cancel']);

Route::get('/report/{id}', [FlightApiController::class, 'report']);

Route::get('/flightSeats/{id}', [FlightApiController::class, 'reportReservedSeats']);