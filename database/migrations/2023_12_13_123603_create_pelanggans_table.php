<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelanggan', 30);
            $table->string('alamat_pelanggan', 30);
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('nomor_hp')->length(13);
            $table->string('photo_profile')->nullable();
            $table->string('level', 10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
        Schema::table('user', function (Blueprint $table){
            $table->dropColumn(['photo_profile']);
        });
    }
};
