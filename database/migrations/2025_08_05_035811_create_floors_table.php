<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('floors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained('room_types')->onDelete('cascade'); 
            $table->integer('floor_number'); 
            $table->timestamps();
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('floor');
            $table->foreignId('floor_id')->nullable()->constrained('floors')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropConstrainedForeignId('floor_id');
            $table->integer('floor')->nullable();
        });

        Schema::dropIfExists('floors');
    }
};
