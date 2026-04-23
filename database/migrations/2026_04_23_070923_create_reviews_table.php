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
    Schema::create('reviews', function (Blueprint $table) {
        $table->char('id', 36)->primary();
        $table->char('user_id', 36);
        $table->char('package_id', 36);
        $table->unsignedTinyInteger('rating');
        $table->text('comment')->nullable();
        $table->boolean('is_approved')->default(false);
        $table->timestamps();
        $table->softDeletes();
        $table->unique(['user_id', 'package_id']);
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
