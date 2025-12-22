<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JenisLayanan;
use App\Models\Pelanggan;
use App\Models\StatusLaundry;

class Pesanan extends Model

{
    use HasFactory;
    protected $table = 'pesanan';
    protected $fillable = [
        'id_jenis_layanan',
        'id_pelanggan',
        'id_status_laundry',
        'total_harga',
        'waktu_pesanan_datang',
        'berat',
        'status_pembayaran',
        'estimasi_selesai',
        'metode_pengambilan',
        'waktu_pesanan_selesai'
    ];
    public $timestamps = false;

    public function jenis_layanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'id_jenis_layanan');
    }
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }
    public function status_laundry()
    {
        return $this->belongsTo(StatusLaundry::class, 'id_status_laundry');
    }

    public function notif()
    {
        return $this->hasOne(Notifikasi::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'id_pesanan');
    }

    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'id_jenis_layanan');
    }
}
