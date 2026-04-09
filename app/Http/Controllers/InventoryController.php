<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
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
    public function show(Inventory $inventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        
    }
    public function updateStock(Request $request, $variantId)
{
    $inventory = Inventory::where('product_variant_id', $variantId)->firstOrFail();

    $validated = $request->validate([
        'stock_quantity' => 'required|integer|min:0'
    ]);

    $inventory->update($validated);

    return response()->json($inventory);
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        //
    }
    public function getAvailableStockAttribute()
{
    return $this->stock_quantity - $this->reserved_quantity;
}
public function releaseStock($quantity)
{
    $this->decrement('reserved_quantity', $quantity);
}
public function reserveStock($quantity)
{
    if ($this->available_stock < $quantity) {
        throw new \Exception('Not enough stock');
    }

    $this->increment('reserved_quantity', $quantity);
}
public function deductStock($quantity)
{
    $this->decrement('stock_quantity', $quantity);
    $this->decrement('reserved_quantity', $quantity);
}
public function isLowStock()
{
    return $this->stock_quantity <= $this->low_stock_threshold;
}
}
