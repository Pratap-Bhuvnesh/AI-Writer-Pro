<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Jobs\SendEmailJob;
use App\Jobs\GenerateInvoiceJob;
use App\Jobs\SendNotificationJob;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::factory(20)->create()->each(function ($order) {            
            // 2. Dispatch jobs
            SendEmailJob::dispatch($order)->delay(now()->addSeconds(5));
            GenerateInvoiceJob::dispatch($order)->delay(now()->addSeconds(5));
            SendNotificationJob::dispatch($order)->delay(now()->addSeconds(5));
        });
    }
}
