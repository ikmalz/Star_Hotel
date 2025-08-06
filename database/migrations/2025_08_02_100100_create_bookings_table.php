<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade'); 
            $table->foreignId('floor_id')->constrained('floors')->onDelete('cascade'); 
            $table->foreignId('room_type_id')->constrained('room_types')->onDelete('cascade');
            $table->foreignId('room_id')->nullable()->constrained('rooms')->onDelete('set null');
            $table->dateTime('checkin_at');
            $table->dateTime('checkout_at');
            $table->integer('price_per_night');
            $table->integer('nights'); 
            $table->integer('price_total'); 
            $table->enum('status_booking', ['pending', 'paid', 'checked_in', 'checkout_pending', 'checked_out', 'canceled'])->default('pending');
            $table->string('code_booking')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
