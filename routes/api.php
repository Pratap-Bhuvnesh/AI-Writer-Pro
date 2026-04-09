<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\AIController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
/* 
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

//Route::middleware('auth:sanctum')->post('/generate', [AIController::class, 'generate']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    Route::post('/generate', [AIController::class, 'generate']);
    Route::get('/history', [AIController::class, 'history']);
}); 
Route::apiResource('products', ProductController::class);

Route::middleware(['auth', 'permission:manage products'])->group(function () {
    Route::apiResource('variants', ProductVariantController::class);
    Route::apiResource('product-images', ProductImageController::class);
    Route::get('/cart', [CartController::class, 'getCart']);
    
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
