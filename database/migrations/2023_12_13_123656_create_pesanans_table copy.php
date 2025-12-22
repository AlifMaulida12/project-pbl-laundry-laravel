<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_jenis_layanan');
            $table->foreign('id_jenis_layanan')->references('id')->on('jenis_layanan');//foreign key jenis_layanan
            $table->unsignedBigInteger('id_pelanggan');
            $table->foreign('id_pelanggan')->references('id')->on('user');//foreign key user
            $table->unsignedBigInteger('id_status_laundry');
            $table->foreign('id_status_laundry')->references('id')->on('status_laundry');//foreign key status_laundry
            $table->integer('total_harga')->nullable()->length(6);// Menjadikan nullable dan memiliki panjang 2
            $table->timestamp('waktu_pesanan_datang')->nullable();
            $table->decimal('berat', 5, 1)->nullable();            
            $table->enum('status_pembayaran', ['belum', 'dibayar']);//data enum untuk status_pembayaran
            $table->timestamp('estimasi_selesai')->nullable();
            $table->enum('metode_pengambilan', ['pickup', 'dropoff']);
            $table->timestamp('waktu_pesanan_selesai')->nullable();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
