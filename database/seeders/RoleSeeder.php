<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
//use Spatie\Permission\Models\Role;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Role::insert([
        ['name' => 'admin'],
        ['name' => 'customer'],
        ['name' => 'manager'],
        ['name' => 'seller'],
    ]);
    }
}
