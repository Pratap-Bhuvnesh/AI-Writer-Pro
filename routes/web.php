<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
//use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SellerOnboardingController;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $featuredProducts = Product::with([
        'category',
        'primaryImage',
        'variants' => fn ($query) => $query->with('inventory')->orderBy('price'),
    ])
        ->latest()
        ->take(6)
        ->get();

    $bestSellerIds = DB::table('order_items')
        ->join('product_variants', 'order_items.variant_id', '=', 'product_variants.id')
        ->select('product_variants.product_id', DB::raw('SUM(order_items.quantity) as sold_quantity'))
        ->groupBy('product_variants.product_id')
        ->orderByDesc('sold_quantity')
        ->limit(6)
        ->pluck('sold_quantity', 'product_id');

    $bestSellers = Product::with([
        'category',
        'primaryImage',
        'variants' => fn ($query) => $query->with('inventory')->orderBy('price'),
    ])
        ->whereIn('id', $bestSellerIds->keys())
        ->get()
        ->sortBy(fn ($product) => $bestSellerIds[$product->id] ?? 0)
        ->reverse()
        ->values();

    if ($bestSellers->isEmpty()) {
        $bestSellers = $featuredProducts->take(4)->values();
    }

    $cartCount = auth()->check()
        ? (int) (optional(auth()->user()->cart()->withSum('items', 'quantity')->first())->items_sum_quantity ?? 0)
        : 0;

    $orderCount = auth()->check()
        ? Order::where('user_id', auth()->id())->count()
        : 0;

    return view('welcome', compact('featuredProducts', 'bestSellers', 'bestSellerIds', 'cartCount', 'orderCount'));
})->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.list');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/sell', [SellerOnboardingController::class, 'create'])->name('seller.apply');
Route::post('/sell', [SellerOnboardingController::class, 'store'])->name('seller.apply.store');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::post('/admin/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::put('/admin/products/{product}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/admin/products/{product}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');
    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::put('/admin/orders/{order}', [AdminController::class, 'updateOrder'])->name('admin.orders.update');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::get('/admin/sellers', [AdminController::class, 'sellers'])->name('admin.sellers');
    Route::put('/admin/sellers/{sellerProfile}', [AdminController::class, 'updateSeller'])->name('admin.sellers.update');
});
Route::middleware(['auth', 'role:admin,manager'])->group(function () {
    Route::get('/manage-products', [AdminController::class, 'products']);
});
Route::middleware(['auth', 'role:seller'])->group(function () {
    Route::get('/seller', [SellerController::class, 'dashboard'])->name('seller.dashboard');
    Route::get('/seller/products', [SellerController::class, 'products'])->name('seller.products');
    Route::post('/seller/products', [SellerController::class, 'storeProduct'])->name('seller.products.store');
    Route::put('/seller/products/{product}', [SellerController::class, 'updateProduct'])->name('seller.products.update');
    Route::delete('/seller/products/{product}', [SellerController::class, 'deleteProduct'])->name('seller.products.delete');
    Route::get('/seller/orders', [SellerController::class, 'orders'])->name('seller.orders');
});
Route::middleware('auth')->group(function () {
    Route::post('/cart/add', [CartItemController::class, 'add'])->name('cart.add');
    Route::put('/cart/item/{id}', [CartItemController::class, 'updateQuantity'])->name('cart.item.update');
    Route::delete('/cart/item/{id}', [CartItemController::class, 'remove'])->name('cart.item.remove');
    Route::get('/cart', [CartController::class, 'getCart'])->name('cart.index');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout', [OrderController::class, 'storeCheckout'])->name('checkout.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});
//Route::resource('products', ProductController::class)->name('allproducts.index');;
Route::post('/inventory/{variant}/update', [InventoryController::class, 'updateStock']);
Route::get('/db-test', function () {
    try {
        DB::connection()->getPdo();
        return "DB Connected ✅";
    } catch (\Exception $e) {
        return "DB Not Connected ❌";
    }
});


Route::get('/run-seed', function () {
    Artisan::call('db:seed', ['--force' => true]);
    return "Seeding Done ✅";
});
Route::get('/clear-config', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    return "Config Cleared";
});
