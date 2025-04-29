<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Flight;
use Faker\Factory as Faker;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $i) {
            Flight::create([
                'origin' => $faker->city,
                'destination' => $faker->city,
                'datetime' => $faker->dateTimeThisYear(),
                'capacity' => $faker->numberBetween(50, 300),
                'created_at' => $faker->dateTimeBetween('now', '+1 year'),
            ]);
        }
    }
}