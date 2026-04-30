<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SellerController extends Controller
{
    public function dashboard(Request $request)
    {
        $sellerId = $request->user()->id;
        $products = Product::where('seller_id', $sellerId)->count();

        $sellerOrderItems = DB::table('order_items')
            ->join('product_variants', 'order_items.variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->where('products.seller_id', $sellerId);

        $orders = (clone $sellerOrderItems)->distinct('order_items.order_id')->count('order_items.order_id');
        $revenue = (clone $sellerOrderItems)->sum(DB::raw('order_items.price * order_items.quantity'));

        $lowStockVariants = ProductVariant::with('product', 'inventory')
            ->whereHas('product', fn ($query) => $query->where('seller_id', $sellerId))
            ->whereHas('inventory', fn ($query) => $query->whereRaw('stock_quantity - reserved_quantity <= low_stock_threshold'))
            ->take(6)
            ->get();

        return view('seller.dashboard', compact('products', 'orders', 'revenue', 'lowStockVariants'));
    }

    public function products(Request $request)
    {
        $products = Product::with('category', 'variants.inventory')
            ->where('seller_id', $request->user()->id)
            ->latest()
            ->paginate(12);
        $categories = Category::orderBy('name')->get();

        return view('seller.products.index', compact('products', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand' => ['nullable', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', 'unique:product_variants,sku'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($request, $validated) {
            $product = Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'category_id' => $validated['category_id'],
                'seller_id' => $request->user()->id,
                'brand' => $validated['brand'] ?? null,
            ]);

            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $validated['sku'],
                'price' => $validated['price'],
                'discount_price' => $validated['discount_price'] ?? null,
                'attributes' => [],
            ]);

            Inventory::create([
                'product_variant_id' => $variant->id,
                'stock_quantity' => $validated['stock_quantity'],
                'reserved_quantity' => 0,
                'low_stock_threshold' => 5,
            ]);
        });

        return back()->with('success', 'Product created.');
    }

    public function updateProduct(Request $request, Product $product)
    {
        abort_unless($product->seller_id === $request->user()->id, 403);

        $variant = $product->variants()->orderBy('price')->first();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($product->id)],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($product, $variant, $validated) {
            $product->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'category_id' => $validated['category_id'],
                'brand' => $validated['brand'] ?? null,
            ]);

            if ($variant) {
                $variant->update([
                    'price' => $validated['price'],
                    'discount_price' => $validated['discount_price'] ?? null,
                ]);
                $variant->inventory()->updateOrCreate(
                    ['product_variant_id' => $variant->id],
                    ['stock_quantity' => $validated['stock_quantity']]
                );
            }
        });

        return back()->with('success', 'Product updated.');
    }

    public function deleteProduct(Request $request, Product $product)
    {
        abort_unless($product->seller_id === $request->user()->id, 403);

        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    public function orders(Request $request)
    {
        $sellerId = $request->user()->id;
        $orders = Order::with('user', 'items.variant.product')
            ->whereHas('items.variant.product', fn ($query) => $query->where('seller_id', $sellerId))
            ->latest()
            ->paginate(15);

        return view('seller.orders.index', compact('orders', 'sellerId'));
    }
}
