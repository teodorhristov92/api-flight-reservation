<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('flight_id')->constrained()->onDelete('cascade');
            $table->integer('seat_number');
            $table->timestamps();
            $table->unique(['flight_id', 'seat_number']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('bookings');
    }
};