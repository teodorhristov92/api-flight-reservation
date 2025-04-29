<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PagesController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('api-page', [PagesController::class, 'showApiTestingPage'])->name('api-testing-page');

Route::resource('flights', FlightController::class);

// Маршрут за създаване на резервация
Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');

// Маршрут за анулиране на резервация
Route::get('/booking/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

// Маршрут за отчет на натовареността на полетите
Route::get('/load-factor-report', [FlightController::class, 'loadFactorReport'])->name('load.factor.report');