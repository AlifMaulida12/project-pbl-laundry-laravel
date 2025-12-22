<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan', 20);
            $table->integer('harga')->nullable()->length(6); // Menjadikan nullable dan memiliki panjang 6
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_layanan');
    }
};
