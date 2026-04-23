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
        $table->char('id', 36)->primary();
        $table->char('booking_id', 36)->unique();
        $table->decimal('amount', 10, 2);
        $table->string('gateway');
        $table->string('transaction_id')->nullable();
        $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
        $table->json('gateway_response')->nullable();
        $table->timestamp('paid_at')->nullable();
        $table->timestamps();
        $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
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
