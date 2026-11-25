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
            $table->id();
            $table->string('booking_code', 50)->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('motorcycle_unit_id')->constrained('motorcycle_units')->onDelete('restrict');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('actual_return_date')->nullable();
            $table->dateTime('hold_expires_at');
            $table->unsignedInteger('duration_days');
            $table->decimal('daily_price', 12, 2);
            $table->decimal('total_price', 12, 2); // Duration_days * Daily_Price
            $table->decimal('late_fee', 12, 2)->default(0); // If actual_return_date > end_date
            $table->decimal('damage_fee', 12, 2)->default(0); // If motorcycle_unit is returned damaged
            $table->enum('status', ['pending_payment', 'waiting_verification', 'approved', 'ongoing', 'completed', 'cancelled'])->default('pending_payment');
            $table->text('notes')->nullable();
            $table->timestamps();
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
