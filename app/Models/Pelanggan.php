<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Pelanggan extends Model implements Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, AuthenticatableTrait, HasRoles;

    protected $table = 'user';
    protected $fillable = ['nama_pelanggan', 'alamat_pelanggan', 'email', 'password', 'nomor_hp', 'photo_profile', 'level'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public $appends = [
        'profile_image_url',
    ];

    public function getProfileImageUrlAttribute()
    {
        if ($this->photo_profile) {
            return asset('/uploads/profile_images/' . $this->photo_profile);
        } else {
            return 'https://ui-avatars.com/api/?background=random&rounded=true&name=' . urlencode($this->nama_pelanggan);
        }
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($pelanggan) {
            $pelanggan->pesanan()->delete();
        });
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_pelanggan');
    }
    //relasi review
    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_pelanggan');
    }
}
