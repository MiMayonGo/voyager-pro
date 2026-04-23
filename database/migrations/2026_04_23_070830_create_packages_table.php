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
    Schema::create('packages', function (Blueprint $table) {
        $table->char('id', 36)->primary();
        $table->string('title');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->decimal('price', 10, 2);
        $table->unsignedInteger('duration_days');
        $table->unsignedInteger('available_slots')->default(0);
        $table->string('cover_image')->nullable();
        $table->enum('status', ['active', 'inactive', 'draft'])->default('draft');
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
