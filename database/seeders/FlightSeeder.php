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

        // Генерираме 20 полета
        foreach (range(1, 20) as $i) {
            Flight::create([
                'origin' => $faker->unique()->regexify('[A-Z]{2}\d{3,4}'), // Генерираме уникален номер на полет (например: AB1234)
                'destination' => $faker->city, // Генерираме случайно име на летище
                'datetime' => $faker->dateTimeThisYear(), // Генерираме случайно друго летище
                'capacity' => $faker->numberBetween(50, 300), // Генерираме случайно време за отпътуване
                'created_at' => $faker->dateTimeBetween('now', '+1 year'), // Генерираме време за пристигане (в рамките на следващата година)
            ]);
        }
    }
}