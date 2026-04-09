<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Support\Str;



class ShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $orders = Order::whereIn('status', ['shipped', 'delivered'])->get();

        foreach ($orders as $order) {

            Shipment::create([
                'order_id' => $order->id,
                'address_id' => $order->user->addresses()->inRandomOrder()->value('id'),

                'courier_name' => fake()->randomElement([
                    'Delhivery',
                    'BlueDart',
                    'DTDC',
                    'Ekart'
                ]),

                'tracking_number' => 'TRK-' . strtoupper(Str::random(10)),

                'shipped_at' => now()->subDays(rand(1, 5)),

                'delivered_at' => $order->status === 'delivered'
                    ? now()
                    : null,
            ]);
        }
    }
}
