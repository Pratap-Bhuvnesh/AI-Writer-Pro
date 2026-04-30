<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\SellerProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'products' => Product::count(),
            'orders' => Order::count(),
            'customers' => User::count(),
            'revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
        ];

        $recentOrders = Order::with('user')->latest()->take(6)->get();
        $lowStockVariants = ProductVariant::with('product', 'inventory')
            ->whereHas('inventory', fn ($query) => $query->whereRaw('stock_quantity - reserved_quantity <= low_stock_threshold'))
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockVariants'));
    }

    public function products()
    {
        $products = Product::with('category', 'variants.inventory')->latest()->paginate(12);
        $categories = Category::orderBy('name')->get();
        $sellers = User::whereHas('roles', fn ($query) => $query->where('name', 'seller'))
            ->orderBy('name')
            ->get();

        return view('admin.products.index', compact('products', 'categories', 'sellers'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'seller_id' => ['nullable', 'exists:users,id'],
            'brand' => ['nullable', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', 'unique:product_variants,sku'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'category_id' => $validated['category_id'],
                'seller_id' => $validated['seller_id'] ?? null,
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
        $variant = $product->variants()->orderBy('price')->first();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($product->id)],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'seller_id' => ['nullable', 'exists:users,id'],
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
                'seller_id' => $validated['seller_id'] ?? null,
                'brand' => $validated['brand'] ?? null,
            ]);

            if ($variant && array_key_exists('price', $validated)) {
                $variant->update([
                    'price' => $validated['price'],
                    'discount_price' => $validated['discount_price'] ?? null,
                ]);
            }

            if ($variant && array_key_exists('stock_quantity', $validated)) {
                $variant->inventory()->updateOrCreate(
                    ['product_variant_id' => $variant->id],
                    ['stock_quantity' => $validated['stock_quantity']]
                );
            }
        });

        return back()->with('success', 'Product updated.');
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    public function orders()
    {
        $orders = Order::with('user', 'items')->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function updateOrder(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'shipped', 'delivered', 'cancelled'])],
            'payment_status' => ['required', Rule::in(['pending', 'paid', 'failed'])],
        ]);

        $order->update($validated);
        $order->payment?->update(['payment_status' => $validated['payment_status']]);

        return back()->with('success', 'Order updated.');
    }

    public function users()
    {
        $users = User::with('roles')->latest()->paginate(15);
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function sellers()
    {
        $sellerProfiles = SellerProfile::with('user.roles')->latest()->paginate(15);

        return view('admin.sellers.index', compact('sellerProfiles'));
    }

    public function updateSeller(Request $request, SellerProfile $sellerProfile)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'approved', 'rejected'])],
        ]);

        DB::transaction(function () use ($sellerProfile, $validated) {
            $sellerProfile->update([
                'status' => $validated['status'],
                'approved_at' => $validated['status'] === 'approved' ? now() : null,
            ]);

            $sellerRole = Role::firstOrCreate(['name' => 'seller'], ['guard_name' => 'web']);

            if ($validated['status'] === 'approved') {
                $sellerProfile->user->roles()->syncWithoutDetaching([$sellerRole->id]);
            } else {
                $sellerProfile->user->roles()->detach($sellerRole->id);
            }
        });

        return back()->with('success', 'Seller application updated.');
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['exists:roles,id'],
        ]);

        $user->update(['is_active' => $validated['is_active']]);
        $user->roles()->sync($validated['role_ids'] ?? []);

        return back()->with('success', 'User updated.');
    }
}
