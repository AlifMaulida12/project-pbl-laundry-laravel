<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pesanan;

class NotifikasiPelanggan extends Model
{
    use HasFactory;
    protected $table = 'notifikasi_pelanggans';

    protected $fillable = [
        'id_pesanan',
        'id_pelanggan',
        'nama_layanan',
        'status'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan');
    }

    // Relasi dengan User (Pelanggan)
    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'id_pelanggan');
    }


}
