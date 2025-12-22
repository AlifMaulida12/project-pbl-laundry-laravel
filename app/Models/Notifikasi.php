<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;
    protected $table ='notifikasi';
    public $timestamps=false;

    public function pesanan(){
        return $this->belongsTo(Pesanan::class, 'id_pesanan');
    }
}
