## Project setup
composer install
windows command - copy .env.example .env
php artisan key:generate

## DB 
Create phpMyAdmin database with name - flight_reservation
php artisan migrate

## Seeding
За да се попълни таблицата
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=BookingSeeder
php artisan db:seed --class=FlightSeeder