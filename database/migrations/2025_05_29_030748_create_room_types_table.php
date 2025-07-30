<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('room_types', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->string('name_type');
            $table->text('facility')->nullable();
            $table->integer('capacity');
            $table->json('photos')->nullable();
            $table->integer('nightly_rate');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
