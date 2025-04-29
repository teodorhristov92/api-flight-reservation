<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FlightApiController;

Route::post('/flights', [FlightApiController::class, 'store']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/book', [FlightApiController::class, 'book']);
Route::delete('/cancel/{id}', [FlightApiController::class, 'cancel']);
Route::get('/report/{id}', [FlightApiController::class, 'report']);