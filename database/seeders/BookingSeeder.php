<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Flight;
use Faker\Factory as Faker;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $flights = Flight::pluck('id')->all();

        foreach (range(1, 20) as $i) {
            Booking::create([
                'user_id' => $faker->randomDigitNotNull(),
                'flight_id' => $faker->randomElement($flights),
                'seat_number' => $faker->randomNumber(2, true),
                'created_at' => $faker->dateTimeThisYear()
            ]);
        }
    }
}
