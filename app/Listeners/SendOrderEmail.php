<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
   // public function handle(object $event): void
   public function handle(OrderPlaced $event)
    {
        \Log::info("Email sent for Order ID: " . $event->order->id);
    }
}
