<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
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
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart){
    //$item = CartItem::findOrFail($id);
    $item = $cart;
    $inventory = $item->variant->inventory;

    $newQty = $request->quantity;
    $difference = $newQty - $item->quantity;

    DB::transaction(function () use ($item, $inventory, $newQty, $difference) {

        if ($difference > 0) {
            // Need more stock
            if ($inventory->available_stock < $difference) {
                throw new \Exception('Not enough stock');
            }
            $inventory->increment('reserved_quantity', $difference);
        } else {
            // Release stock
            $inventory->decrement('reserved_quantity', abs($difference));
        }

        $item->update(['quantity' => $newQty]);
    });

    return response()->json(['message' => 'Cart updated']);
}
   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }
    

    public function add(Request $request)
    {
        $validated = $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id()
        ]);

        $variant = ProductVariant::findOrFail($validated['variant_id']);
        $inventory = $variant->inventory;

        DB::transaction(function () use ($cart, $variant, $inventory, $validated) {

            // Check stock
            if (($inventory->stock_quantity - $inventory->reserved_quantity) < $validated['quantity']) {
                throw new \Exception('Out of stock');
            }

            // Reserve stock
            $inventory->increment('reserved_quantity', $validated['quantity']);

            // Add or update cart item
            CartItem::updateOrCreate(
                [
                    'cart_id' => $cart->id,
                    'product_variant_id' => $variant->id
                ],
                [
                    'quantity' => DB::raw('quantity + ' . $validated['quantity'])
                ]
            );
        });

        return response()->json(['message' => 'Added to cart']);
    }
    public function remove($id)
    {
        $item = CartItem::findOrFail($id);
        $inventory = $item->variant->inventory;

        DB::transaction(function () use ($item, $inventory) {
            // Release reserved stock
            $inventory->decrement('reserved_quantity', $item->quantity);

            $item->delete();
        });

        return response()->json(['message' => 'Item removed']);
    }
}
