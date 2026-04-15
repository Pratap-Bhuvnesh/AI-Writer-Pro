<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Jobs\SendEmailJob;
use App\Jobs\GenerateInvoiceJob;
use App\Jobs\SendNotificationJob;
use Illuminate\Support\Str;
use App\Events\OrderPlaced;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
    public function applyCoupon($code, $cartTotal)
    {
        $coupon = Coupon::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            throw new \Exception('Invalid coupon');
        }

        // Expiry check
        if ($coupon->expiry_date && $coupon->expiry_date < now()) {
            throw new \Exception('Coupon expired');
        }

        // Usage limit check
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            throw new \Exception('Coupon usage limit reached');
        }

        // Calculate discount
        if ($coupon->discount_type === 'percentage') {
            $discount = ($cartTotal * $coupon->discount_value) / 100;
        } else {
            $discount = $coupon->discount_value;
        }

        return min($discount, $cartTotal); // prevent negative total
    }
    public function placeOrder(Request $request)
    {                //dd([$request->user()->id,\Auth::id()]);
        $order = Order::create([
            'user_id' => $request->user()->id, // ya auth()->id()
            'total_amount' => $request->total_amount,

            'status' => 'pending',
            'payment_status' => 'pending',

            'order_number' => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),

            'shipping_address' => $request->shipping_address,

            'payment_method' => $request->payment_method
        ]);
    
    event(new OrderPlaced($order));
 /*    return "Order placed!";
    // 2. Dispatch jobs
    SendEmailJob::dispatch($order);
    GenerateInvoiceJob::dispatch($order);
    SendNotificationJob::dispatch($order); */

    return response()->json([
        'message' => 'Order placed successfully!',
        'order_id' => $order->id
    ]);
    }
}
