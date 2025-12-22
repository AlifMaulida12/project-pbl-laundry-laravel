<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisLayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jenisLayanan = [
            [
                'id' => 1,
                'nama_layanan' => 'cuci kering',
                'harga' => 5000,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 2,
                'nama_layanan' => 'cuci basah',
                'harga' => 4000,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 3,
                'nama_layanan' => 'strika',
                'harga' => 4000,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 4,
                'nama_layanan' => 'cuci express',
                'harga' => 10000,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 5,
                'nama_layanan' => 'seprei',
                'harga' => 7000,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 6,
                'nama_layanan' => 'sepatu',
                'harga' => 10000,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 7,
                'nama_layanan' => 'selimut',
                'harga' => 15000,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 8,
                'nama_layanan' => 'boneka',
                'harga' => 15000,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 9,
                'nama_layanan' => 'laundry cuci setrika',
                'harga' => 6000,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 10,
                'nama_layanan' => 'bed cover',
                'harga' => 35000,
                'created_at' => null,
                'updated_at' => null,
            ],
        ];

        // Insert data
        DB::table('jenis_layanan')->insert($jenisLayanan);
    }
}
