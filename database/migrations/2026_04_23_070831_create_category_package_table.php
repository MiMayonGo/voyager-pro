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
    Schema::create('category_package', function (Blueprint $table) {
        $table->unsignedBigInteger('category_id');
        $table->char('package_id', 36);
        $table->primary(['category_id', 'package_id']);
        $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_package');
    }
};
