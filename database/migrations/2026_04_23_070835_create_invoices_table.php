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
    Schema::create('invoices', function (Blueprint $table) {
        $table->char('id', 36)->primary();
        $table->char('booking_id', 36)->unique();
        $table->string('invoice_number')->unique();
        $table->decimal('amount', 10, 2);
        $table->timestamp('issued_at')->nullable();
        $table->date('due_date')->nullable();
        $table->string('file_path')->nullable();
        $table->timestamps();
        $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
