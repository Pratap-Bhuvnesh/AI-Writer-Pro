<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Order;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();

        foreach ($orders as $order) {

            $status = $order->payment_status;

            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $order->payment_method ?? 'cod',
                'payment_status' => $status,
                'transaction_id' => $status === 'paid'
                    ? 'TXN-' . strtoupper(\Illuminate\Support\Str::random(10))
                    : null,
                'paid_at' => $status === 'paid' ? now() : null,
            ]);
        }
    }
}
