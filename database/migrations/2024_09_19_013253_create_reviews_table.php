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
            $table->id();
            $table->foreignId('id_pelanggan')->constrained('user')->onDelete('cascade');//relasi
            $table->foreignId('id_pesanan')->constrained('pesanan')->onDelete('cascade');//relasi
            $table->tinyInteger('rating')->unsigned(); // Nilai rating (1-5)
            $table->text('review')->nullable(); // Ulasan dari pelanggan
            $table->timestamps();
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
