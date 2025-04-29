## Project setup

composer install

Windows command: copy .env.example .env

php artisan key:generate

## DB 

Create phpMyAdmin database with name - flight_reservation

php artisan migrate

## Seeding

For seeding db

php artisan db:seed --class=UserSeeder

php artisan db:seed --class=FlightSeeder

php artisan db:seed --class=BookingSeeder

php artisan db:seed --class=SeatSeeder
