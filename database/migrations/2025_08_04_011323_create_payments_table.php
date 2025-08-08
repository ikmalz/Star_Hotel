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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->integer('amount');
            $table->enum('payment_method', [
                'dana',
                'gopay',
                'ovo',
                'shopeepay',
                'linkaja',
                'qris',
                'bca',
                'bni',
                'bri',
                'mandiri',
                'credit_card',
                'debit_card',
                'cash'
            ]);
            $table->string('payment_proof')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('va_number')->nullable();
            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'refunded',
                'refund_requested'
            ])->default('pending');
            $table->integer('refund_amount')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->text('refund_reason')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
