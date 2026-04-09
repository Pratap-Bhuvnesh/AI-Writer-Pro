<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Addresses;
use App\Models\User;

class AddresesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $users = User::all();
        foreach ($users as $user) {

            // create multiple addresses
            $addresses = Addresses::factory(2)->create([
                'user_id' => $user->id
            ]);

            // set one as default
            $addresses->first()->update([
                'is_default' => true
            ]);
        }
    }
}
