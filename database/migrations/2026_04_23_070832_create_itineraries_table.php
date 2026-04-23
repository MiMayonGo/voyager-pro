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
    Schema::create('itineraries', function (Blueprint $table) {
        $table->char('id', 36)->primary();
        $table->char('package_id', 36);
        $table->unsignedInteger('day_number');
        $table->string('title');
        $table->text('description')->nullable();
        $table->json('meals_included')->nullable();
        $table->string('accommodation')->nullable();
        $table->timestamps();
        $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itineraries');
    }
};
