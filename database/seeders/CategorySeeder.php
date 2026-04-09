<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $electronics = \App\Models\Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics'
        ]);

        \App\Models\Category::create([
            'name' => 'Mobiles',
            'slug' => 'mobiles',
            'parent_id' => $electronics->id
        ]);

        \App\Models\Category::create([
            'name' => 'Laptops',
            'slug' => 'laptops',
            'parent_id' => $electronics->id
        ]);
    }
}
