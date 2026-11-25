<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('motorcycles', function (Blueprint $table) {
            $table->id();
            $table->string('brand', 100);                           // Honda, Yamaha, ...
            $table->string('model', 100);                           // Vario 160, NMAX, ...
            $table->enum('category', ['matic', 'manual', 'sport']);
            $table->integer('cc');                                  // kapasitas mesin
            $table->decimal('daily_price', 12, 2);                  // harga sewa per 24 jam
            $table->text('description')->nullable();
            $table->string('image')->nullable();                    // foto katalog
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('motorcycles');
    }
};