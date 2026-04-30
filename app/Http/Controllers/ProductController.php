<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        if ($request->is('api/*')) {
            return Product::with('category')->get();
        }

        $products = Product::with([
            'category',
            'primaryImage',
            'variants' => fn ($query) => $query->with('inventory')->orderBy('price'),
        ])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products'));
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
    public function store(Request $request){
    /*   $validated = $request->validate([
        'name' => 'required|string',
        'description' => 'nullable|string',
        'category_id' => 'required|exists:categories,id',
        'brand' => 'nullable|string',
    ]);
    return Product::create($validated); */ 
     // ✅ Validation
    $request->validate([        
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category_id' => 'required|exists:categories,id',
        'brand' => 'nullable|string',
        'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // ✅ Decode variants
    $variants = json_decode($request->variants, true);


    DB::beginTransaction();

        try {
            // 1. Create Product
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
            ]);
            if(isset($request->variants)){
                // 2. Create Variants
                foreach ($request->variants as $variant) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $variant['sku'],
                        'price' => $variant['price'],
                        'discount_price' => $variant['discount_price'] ?? null,
                        'attributes' => json_encode($variant['attributes']),
                    ]);
                }
            }
            

            // 3. Upload Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $path,
                        'is_primary' => $index === 0,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Product created successfully',
                'product' => $product->load('variants', 'images')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    
}

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load([
            'category',
            'images',
            'variants' => fn ($query) => $query->with('inventory')->orderBy('price'),
        ]);

        if (request()->is('api/*')) {
            return $product;
        }

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
         $product->update($request->all());
        return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
