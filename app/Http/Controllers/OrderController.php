<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Jobs\SendEmailJob;
use App\Jobs\GenerateInvoiceJob;
use App\Jobs\SendNotificationJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Events\OrderPlaced;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('items.variant.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
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
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items.variant.product.primaryImage', 'payment');

        return view('orders.show', compact('order'));
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

    public function checkout(Request $request)
    {
        $cart = Cart::with('items.variant.product.primaryImage', 'items.variant.inventory')
            ->firstOrCreate(['user_id' => $request->user()->id]);

        return view('checkout.index', compact('cart'));
    }

    public function storeCheckout(Request $request)
    {
        $validated = $request->validate([
            'address_line1' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40'],
            'payment_method' => ['required', 'in:cod,upi,card'],
        ]);

        $cart = Cart::with('items.variant.inventory', 'items.variant.product')
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            $order = DB::transaction(function () use ($cart, $request, $validated) {
                $total = $cart->items->sum(function ($item) {
                    $price = $item->variant?->discount_price ?? $item->variant?->price ?? 0;

                    return (float) $price * $item->quantity;
                });

                $shippingAddress = collect([
                    $validated['address_line1'],
                    $validated['city'],
                    $validated['state'],
                    $validated['postal_code'],
                    $validated['country'],
                    $validated['phone'] ? 'Phone: ' . $validated['phone'] : null,
                ])->filter()->implode(', ');

                $paymentStatus = $validated['payment_method'] === 'cod' ? 'pending' : 'paid';

                $order = Order::create([
                    'user_id' => $request->user()->id,
                    'total_amount' => $total,
                    'status' => 'pending',
                    'payment_status' => $paymentStatus,
                    'order_number' => $this->newOrderNumber(),
                    'shipping_address' => $shippingAddress,
                    'payment_method' => $validated['payment_method'],
                ]);

                foreach ($cart->items as $item) {
                    $variant = $item->variant;
                    $inventory = $variant?->inventory;

                    if (! $variant || ! $inventory || $inventory->reserved_quantity < $item->quantity) {
                        throw new \RuntimeException('One of your cart items is no longer available.');
                    }

                    $unitPrice = $variant->discount_price ?? $variant->price;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'variant_id' => $variant->id,
                        'quantity' => $item->quantity,
                        'price' => $unitPrice,
                    ]);

                    $inventory->decrement('stock_quantity', $item->quantity);
                    $inventory->decrement('reserved_quantity', $item->quantity);
                }

                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => $paymentStatus,
                    'transaction_id' => $validated['payment_method'] === 'cod' ? null : 'TXN-' . strtoupper(Str::random(10)),
                    'paid_at' => $paymentStatus === 'paid' ? now() : null,
                    'gateway_response' => [
                        'source' => 'demo_checkout',
                    ],
                ]);

                $cart->items()->delete();

                return $order;
            });
        } catch (\RuntimeException $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }

        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully.');
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

    private function newOrderNumber(): string
    {
        do {
            $number = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
