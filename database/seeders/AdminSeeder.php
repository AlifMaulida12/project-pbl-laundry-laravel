<?php

namespace Database\Seeders;
use App\Models\Pelanggan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
            $admin = Pelanggan::create([//buat user admin
                'nama_pelanggan'=>'Ita',
                'alamat_pelanggan'=>'Bwi',
                'email'=>'admin@gmail.com',
                'password'=>bcrypt('1'),
                'nomor_hp'=>'082',
                'photo_profile' => null,
                'level' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $admin->assignRole('admin');//beri role admin kepada user Ita
        }
    }
}
