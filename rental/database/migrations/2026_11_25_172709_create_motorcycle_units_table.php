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
        Schema::create('motorcycle_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('motorcycle_id')->constrained('motorcycles')->onDelete('restrict');
            $table->string('license_plate', 20)->unique();
            $table->year('year');
            $table->string('color', 50);
            $table->string('unit_condition', 50);
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motorcycle_units');
    }
};
