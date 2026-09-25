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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_model_id')->constrained()->onDelete('cascade');
            $table->integer('year');
            $table->decimal('price', 12, 2);
            $table->integer('mileage');
            $table->string('fuel_type'); // Gasoline, Diesel, Electric, etc.
            $table->string('transmission'); // Manual, Automatic
            $table->text('description')->nullable();
            $table->json('images')->nullable();
            $table->string('status')->default('available'); // available, reserved, sold
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
