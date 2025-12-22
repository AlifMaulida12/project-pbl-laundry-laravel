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
        Schema::create('notifikasi_pelanggans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pelanggan')->constrained('user')->onDelete('cascade');
            $table->foreignId('id_pesanan')->constrained('pesanan')->onDelete('cascade');
            $table->string('nama_layanan');
            $table->string('status')->default('selesai');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi_pelanggans');
    }
};
