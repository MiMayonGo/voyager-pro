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
    Schema::create('bookings', function (Blueprint $table) {
        $table->char('id', 36)->primary();
        $table->char('user_id', 36);
        $table->char('package_id', 36);
        $table->unsignedInteger('slots_booked');
        $table->decimal('total_price', 10, 2);
        $table->date('travel_date');
        $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
        $table->text('special_requests')->nullable();
        $table->timestamp('cancelled_at')->nullable();
        $table->timestamps();
        $table->softDeletes();
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
