<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }
     public function backoff()
    {
        return [15, 15]; // 👈 IMPORTANT (delay between retries)
    }
    public function handle()
    {
       
        \Log::info("Email sent for Order ID: " . $this->order->id);    
         //\Log::info("Attempt: " . $this->attempts());    
        // Force error
        throw new \Exception("Email sending failed!");
        // Mail::to($this->order->email)->send(new OrderMail($this->order));
    }
}
