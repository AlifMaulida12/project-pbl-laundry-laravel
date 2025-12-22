<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusLaundrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                'id' => 1,
                'status' => 'dalam antrian',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 2,
                'status' => 'diproses',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 3,
                'status' => 'selesai',
                'created_at' => null,
                'updated_at' => null,
            ],
        ];

        // Insert data using Query Builder
        DB::table('status_laundry')->insert($statuses);
    }
}
