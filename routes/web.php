<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
//use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CartController;
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
    return view('welcome');
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    //Route::get('/admin/dashboard', [AdminController::class, 'index']);
});
Route::middleware(['auth', 'role:admin,manager'])->group(function () {
    Route::get('/manage-products', [ProductController::class, 'index']);
});
Route::middleware('auth')->group(function () {
    Route::post('/cart/add', [CartItemController::class, 'add']);
    Route::put('/cart/item/{id}', [CartItemController::class, 'update']);
    Route::delete('/cart/item/{id}', [CartItemController::class, 'remove']);
});
//Route::resource('products', ProductController::class)->name('allproducts.index');;
Route::post('/inventory/{variant}/update', [InventoryController::class, 'updateStock']);
Route::middleware('auth')->get('/cart', [CartController::class, 'getCart']);
