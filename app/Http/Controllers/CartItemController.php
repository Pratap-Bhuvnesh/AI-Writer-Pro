<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartItemController extends Controller
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
    public function show(CartItem $cartItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CartItem $cartItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        //
    }

    public function updateQuantity(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $item = CartItem::with('variant.inventory', 'cart')->findOrFail($id);

        abort_unless($item->cart->user_id === $request->user()->id, 403);

        $inventory = $item->variant?->inventory;

        if (! $inventory) {
            return $this->cartResponse($request, 'This product is not available right now.', false);
        }

        $newQuantity = (int) $validated['quantity'];
        $difference = $newQuantity - $item->quantity;

        if ($difference > 0 && ($inventory->stock_quantity - $inventory->reserved_quantity) < $difference) {
            return $this->cartResponse($request, 'Not enough stock is available for this product.', false);
        }

        DB::transaction(function () use ($item, $inventory, $newQuantity, $difference) {
            if ($difference > 0) {
                $inventory->increment('reserved_quantity', $difference);
            } elseif ($difference < 0) {
                $inventory->decrement('reserved_quantity', abs($difference));
            }

            $item->update(['quantity' => $newQuantity]);
        });

        return $this->cartResponse($request, 'Cart updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $cartItem)
    {
        //
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'variant_id' => ['required', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = Cart::firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        $variant = ProductVariant::with('inventory')->findOrFail($validated['variant_id']);
        $inventory = $variant->inventory;

        if (! $inventory) {
            return $this->cartResponse($request, 'This product is not available right now.', false);
        }

        try {
            DB::transaction(function () use ($cart, $variant, $inventory, $validated) {
                $cartItem = CartItem::firstOrNew([
                    'cart_id' => $cart->id,
                    'product_variant_id' => $variant->id,
                ]);

                $requestedQuantity = (int) $validated['quantity'];
                $availableStock = $inventory->stock_quantity - $inventory->reserved_quantity;

                if ($availableStock < $requestedQuantity) {
                    throw new \RuntimeException('Not enough stock is available for this product.');
                }

                $inventory->increment('reserved_quantity', $requestedQuantity);
                $cartItem->quantity = ($cartItem->exists ? $cartItem->quantity : 0) + $requestedQuantity;
                $cartItem->save();
            });
        } catch (\RuntimeException $exception) {
            return $this->cartResponse($request, $exception->getMessage(), false);
        }

        return $this->cartResponse($request, 'Product added to cart.');
    }

    public function remove($id)
    {
        $item = CartItem::with('variant.inventory', 'cart')->findOrFail($id);
        abort_unless($item->cart->user_id === request()->user()->id, 403);

        $inventory = $item->variant?->inventory;

        DB::transaction(function () use ($item, $inventory) {
            if ($inventory) {
                $inventory->decrement('reserved_quantity', $item->quantity);
            }

            $item->delete();
        });

        return $this->cartResponse(request(), 'Item removed from cart.');
    }

    private function cartResponse(Request $request, string $message, bool $success = true)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => $message], $success ? 200 : 422);
        }

        $flashKey = $success ? 'success' : 'error';

        return back()->with($flashKey, $message);
    }
}
