<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Seat;
use App\Models\Flight;
use Faker\Factory as Faker;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $flights = Flight::all();

        foreach ($flights as $flight) {
            $seatCount = $faker->numberBetween(1, 5);

            foreach (range(1, $seatCount) as $i) {
                Seat::create([
                    'seat_number' => $i,
                    'class' => $faker->randomElement(['Economy', 'Business', 'First']),
                    'flight_id' => $flight->id,
                ]);
            }
        }
    }
}
