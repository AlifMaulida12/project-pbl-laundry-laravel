<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //ini adalah permission yang akan dibuat
        Permission::create(['name'=>'akses-penuh']);

         //ini adalah rolenya
         Role::create(['name'=>'admin']);

         //beri permission kepada role yang sudah di buat
        $roleAdmin = Role::findByName('admin');
        $roleAdmin->givePermissionTo('akses-penuh');
    }
}
