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
        Schema::create('booking_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('restrict');
            $table->enum('inspection_type', ['before', 'after']);
            $table->foreignId('inspected_by')->constrained('users')->onDelete('restrict');
            $table->text('condition_notes');
            $table->unsignedInteger('odometer');
            $table->enum('fuel_level', ['low', 'medium', 'high'])->default('medium');
            $table->string('photo_path', 255)->nullable();
            $table->dateTime('inspection_date');
            $table->unique(['booking_id', 'inspection_type']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_inspections');
    }
};
